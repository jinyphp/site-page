<?php
/**
 * Modified Parsedown
 */
namespace Jiny\Pages\Http\Parsedown;

use \Jiny\Html\CTag;

trait Handler
{
    #
    # Handlers
    # 요소 생성
    protected function element(array $Element)
    {
        if ($this->safeMode)
        {
            $Element = $this->sanitiseElement($Element);
        }

        ## 시작테그
        ///$markup = '<'.$Element['name'];
        $tag = new CTag($Element['name'], true);

        ## 속성값
        if (isset($Element['attributes']))
        {
            foreach ($Element['attributes'] as $name => $value)
            {
                if ($value === null)
                {
                    continue;
                }

                ///$markup .= ' '.$name.'="'.self::escape($value).'"';
                $tag->setAttribute($name, self::escape($value));
            }
        }

        $permitRawHtml = false;

        if (isset($Element['text']))
        {
            $text = $Element['text'];
        } else
        // very strongly consider an alternative if you're writing an
        // extension
        if (isset($Element['rawHtml']))
        {
            $text = $Element['rawHtml'];

            $allowRawHtmlInSafeMode = isset($Element['allowRawHtmlInSafeMode']) && $Element['allowRawHtmlInSafeMode'];
            $permitRawHtml = !$this->safeMode || $allowRawHtmlInSafeMode;
        }

        ## 닫기 테그
        if (isset($text)) {
            ///$markup .= '>';

            ## ~
            if (!isset($Element['nonNestables']))
            {
                $Element['nonNestables'] = [];
            }

            // 핸들러 호출
            if (isset($Element['handler']))
            {
                ///$markup .= $this->{$Element['handler']}($text, $Element['nonNestables']);
                $tag->addHtml( $this->{$Element['handler']}($text, $Element['nonNestables']) );
            }
            elseif (!$permitRawHtml)
            {
                ///$markup .= self::escape($text, true);
                $tag->addHtml( self::escape($text, true) );
            }
            else
            {
                ///$markup .= $text;
                $tag->addItem($text);
            }

            ##~
            ///$markup .= '</'.$Element['name'].'>';
        } else {
            ## 단일테그
            ///$markup .= ' />';
            $tag->paired = false;
        }

        return $tag;
    }

    protected function elements(array $Elements)
    {
        $markup = '';

        foreach ($Elements as $Element) {
            $markup .= "\n" . $this->element($Element);
        }

        $markup .= "\n";
        // dd($markup);
        return $markup;
    }

    # 
    protected function li($lines)
    {
        $markup = $this->lines($lines);

        $trimmedMarkup = trim($markup);

        if ( ! in_array('', $lines) && substr($trimmedMarkup, 0, 3) === '<p>')
        {
            $markup = $trimmedMarkup;
            $markup = substr($markup, 3);

            $position = strpos($markup, "</p>");

            $markup = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    #
    # Inline Elements
    #

    protected $InlineTypes = array(
        '"' => array('SpecialCharacter'),
        '!' => array('Image'),
        '&' => array('SpecialCharacter'),
        '*' => array('Emphasis'),
        ':' => array('Url'),
        '<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),
        '>' => array('SpecialCharacter'),
        '[' => array('Link'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough'),
        '\\' => array('EscapeSequence'),
    );

    protected $inlineMarkerList = '!"*_&[:<>`~\\';

    #
    # ~
    #
    // You can also parse inline markdown only:
    // echo $Parsedown->line('Hello _Parsedown_!'); # prints: Hello <em>Parsedown</em>!
    public function line($text, $nonNestables=array())
    {
        //dump("line 핸들러". $text);
        $markup = '';

        # $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inlineMarkerList))
        {
            $marker = $excerpt[0];
            $markerPosition = strpos($text, $marker);

            //dump($excerpt);

            $Excerpt = array('text' => $excerpt, 'context' => $text);
            foreach ($this->InlineTypes[$marker] as $inlineType)
            {
                # check to see if the current inline type is nestable in the current context

                if ( ! empty($nonNestables) && in_array($inlineType, $nonNestables))
                {
                    continue;
                }

                $Inline = $this->{'inline'.$inlineType}($Excerpt);
                if ( ! isset($Inline))
                {
                    continue;
                }

                # makes sure that the inline belongs to "our" marker
                if (isset($Inline['position']) and $Inline['position'] > $markerPosition)
                {
                    continue;
                }

                # sets a default inline position
                if ( ! isset($Inline['position']))
                {
                    $Inline['position'] = $markerPosition;
                }

                # cause the new element to 'inherit' our non nestables

                foreach ($nonNestables as $non_nestable)
                {
                    $Inline['element']['nonNestables'][] = $non_nestable;
                }

                # the text that comes before the inline
                $unmarkedText = substr($text, 0, $Inline['position']);

                # compile the unmarked text
                $markup .= $this->unmarkedText($unmarkedText);

                # compile the inline
                $markup .= isset($Inline['markup']) ? $Inline['markup'] : $this->element($Inline['element']);

                # remove the examined text
                $text = substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            # the marker does not belong to an inline

            $unmarkedText = substr($text, 0, $markerPosition + 1);

            $markup .= $this->unmarkedText($unmarkedText);

            $text = substr($text, $markerPosition + 1);
        }

        $markup .= $this->unmarkedText($text);

        return $markup;
    }

    #
    # ~
    #

    protected function inlineCode($Excerpt)
    {
        $marker = $Excerpt['text'][0];

        if (preg_match('/^('.$marker.'+)[ ]*(.+?)[ ]*(?<!'.$marker.')\1(?!'.$marker.')/s', $Excerpt['text'], $matches))
        {
            $text = $matches[2];
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'code',
                    'text' => $text,
                ),
            );
        }
    }

    protected function inlineEmailTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $Excerpt['text'], $matches))
        {
            $url = $matches[1];

            if ( ! isset($matches[2]))
            {
                $url = 'mailto:' . $url;
            }

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );
        }
    }

    protected function inlineEmphasis($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]))
        {
            return;
        }

        $marker = $Excerpt['text'][0];

        if ($Excerpt['text'][1] === $marker and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
        {
            $emphasis = 'strong';
        }
        elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches))
        {
            $emphasis = 'em';
        }
        else
        {
            return;
        }

        return array(
            'extent' => strlen($matches[0]),
            'element' => array(
                'name' => $emphasis,
                'handler' => 'line',
                'text' => $matches[1],
            ),
        );
    }

    protected function inlineEscapeSequence($Excerpt)
    {
        if (isset($Excerpt['text'][1]) and in_array($Excerpt['text'][1], $this->specialCharacters))
        {
            return array(
                'markup' => $Excerpt['text'][1],
                'extent' => 2,
            );
        }
    }

    protected function inlineImage($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[')
        {
            return;
        }

        $Excerpt['text']= substr($Excerpt['text'], 1);

        $Link = $this->inlineLink($Excerpt);

        if ($Link === null)
        {
            return;
        }

        $Inline = array(
            'extent' => $Link['extent'] + 1,
            'element' => array(
                'name' => 'img',
                'attributes' => array(
                    'src' => $Link['element']['attributes']['href'],
                    'alt' => $Link['element']['text'],
                ),
            ),
        );

        $Inline['element']['attributes'] += $Link['element']['attributes'];

        unset($Inline['element']['attributes']['href']);

        return $Inline;
    }

    protected function inlineLink($Excerpt)
    {
        $Element = array(
            'name' => 'a',
            'handler' => 'line',
            'nonNestables' => array('Url', 'Link'),
            'text' => null,
            'attributes' => array(
                'href' => null,
                'title' => null,
            ),
        );

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches))
        {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        }
        else
        {
            return;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches))
        {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2]))
            {
                $Element['attributes']['title'] = substr($matches[2], 1, - 1);
            }

            $extent += strlen($matches[0]);
        }
        else
        {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches))
            {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            }
            else
            {
                $definition = strtolower($Element['text']);
            }

            if ( ! isset($this->DefinitionData['Reference'][$definition]))
            {
                return;
            }

            $Definition = $this->DefinitionData['Reference'][$definition];

            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }

    protected function inlineMarkup($Excerpt)
    {
        if ($this->markupEscaped or $this->safeMode or strpos($Excerpt['text'], '>') === false)
        {
            return;
        }

        if ($Excerpt['text'][1] === '/' and preg_match('/^<\/\w[\w-]*[ ]*>/s', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] === '!' and preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] !== ' ' and preg_match('/^<\w[\w-]*(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*\/?>/s', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }
    }

    protected function inlineSpecialCharacter($Excerpt)
    {
        if ($Excerpt['text'][0] === '&' and ! preg_match('/^&#?\w+;/', $Excerpt['text']))
        {
            return array(
                'markup' => '&amp;',
                'extent' => 1,
            );
        }

        $SpecialCharacter = array('>' => 'gt', '<' => 'lt', '"' => 'quot');

        if (isset($SpecialCharacter[$Excerpt['text'][0]]))
        {
            return array(
                'markup' => '&'.$SpecialCharacter[$Excerpt['text'][0]].';',
                'extent' => 1,
            );
        }
    }

    protected function inlineStrikethrough($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]))
        {
            return;
        }

        if ($Excerpt['text'][1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excerpt['text'], $matches))
        {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'del',
                    'text' => $matches[1],
                    'handler' => 'line',
                ),
            );
        }
    }

    protected function inlineUrl($Excerpt)
    {
        if ($this->urlsLinked !== true or ! isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/')
        {
            return;
        }

        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE))
        {
            $url = $matches[0][0];

            $Inline = array(
                'extent' => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element' => array(
                    'name' => 'a',
                    'text' => $url,
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );

            return $Inline;
        }
    }

    protected function inlineUrlTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $Excerpt['text'], $matches))
        {
            $url = $matches[1];

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $url,
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );
        }
    }
}
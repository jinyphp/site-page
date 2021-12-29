<?php
/**
 * Modified Parsedown
 */
namespace Jiny\Pages\Http\Parsedown;

trait Text
{
    #
    # Lines Tag
    #
    protected $BlockTypes = array(
        '#' => array('Header'), // Headers

        '*' => array('Rule', 'List'), // un-ordered list
        '+' => array('List'), // un-ordered list
        '-' => array('SetextHeader', 'Table', 'Rule', 'List'), 

        '0' => array('List'), // ordered list
        '1' => array('List'),
        '2' => array('List'),
        '3' => array('List'),
        '4' => array('List'),
        '5' => array('List'),
        '6' => array('List'),
        '7' => array('List'),
        '8' => array('List'),
        '9' => array('List'),

        ':' => array('Table'),
        '<' => array('Comment', 'Markup'),
        '=' => array('SetextHeader'),
        '>' => array('Quote'), // Blockquotes
        '[' => array('Reference'),
        '_' => array('Rule'),
        '`' => array('FencedCode'),
        '|' => array('Table'),
        '~' => array('FencedCode'),
    );

    

    # 문서를 파싱합니다.
    public function text($text)
    {
        # make sure no definitions are set
        $this->DefinitionData = [];

        $text = $this->lineBreaks($text);
        $lines = $this->splitLines($text);
        
        # iterate through lines to identify blocks
        return $this->lines($lines);
    }

    private function lineBreaks($text)
    {
        # standardize line breaks
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        # remove surrounding line breaks
        return trim($text, "\n");
    }

    private function splitLines($text)
    {
        # split text into lines
        # 문석을 위하여 한줄단위로 변환배열
        return explode("\n", $text);
    }

    #
    # Blocks
    # 분리된 문서배열을 변환합니다.
    protected function lines(array $lines)
    {
        $Blocks = $this->currentBlock($lines);      
        return $this->blockRender($Blocks); # 마크업 코드 생성
    }

    private function intentCheck($line)
    {
        ## 인덴트 공백갯수
        $indent = 0;
        while (isset($line[$indent]) && $line[$indent] === ' ')
        {
            $indent++;
        }

        $text = $indent > 0 ? substr($line, $indent) : $line;
     
        return [
            'body' => $line, // 원본 데이터
            'indent' => $indent, 
            'text' => $text // indent 공백 제거한 문자 
        ];
    }

    private function currentBlock($lines)
    {
        $CurrentBlock = null; // 상태값

        foreach ($lines as $line)
        {
            //dump("시작=".$line);
            ## is공백문자열인지 검사
            if (chop($line) === '')
            {
                if (isset($CurrentBlock))
                {
                    // 줄번역 종료
                    $CurrentBlock['interrupted'] = true;
                }
                continue;
            }

            ## 첫라인 텝문자 시작검사
            $line = $this->tabInLine($line);
            $Line = $this->intentCheck($line);



            # ~
            if (isset($CurrentBlock['continuable']))
            {
                // 메소드 호출하여 Block 가지고 오기
                $method = 'block'.$CurrentBlock['type'].'Continue';
                $Block = $this->{$method}($Line, $CurrentBlock);

                //dd($Block);

                if (isset($Block)) {
                    $CurrentBlock = $Block; // 블럭 저장
                    continue;
                } else {
                    // 계속 분석할 블럭이 있지만,
                    // 검출을 하지 못한 경우
                    if ($this->isBlockCompletable($CurrentBlock['type'])) {
                        $method = 'block'.$CurrentBlock['type'].'Complete';
                        $CurrentBlock = $this->{$method}($CurrentBlock);
                    }
                }
            }


            # ~
            # 블럭해석
            # 예상타입
            $blockTypes = $this->blockTypes($Line['text'][0]);       
            foreach ($blockTypes as $blockType)
            {
                $method = 'block'.$blockType;
                $Block = $this->{$method}($Line, $CurrentBlock);
                //dump($CurrentBlock);


                // 블럭 검출...
                if (isset($Block)) {                    
                    $Block['type'] = $blockType; //검출된 블럭 타입명 설정

                    //식별이 되지 않은 경우
                    if ( ! isset($Block['identified'])) {
                        //dump("Blocks 저장...");
                        $Blocks []= $CurrentBlock; // 이전 블럭을 목록에 저장
                        $Block['identified'] = true;
                    }
                    

                    // 해당 블럭의 continue 메소드가 있는경우
                    if ($this->isBlockContinuable($blockType))
                    {
                        $Block['continuable'] = true; //블럭 속성 연결
                    }

                    $CurrentBlock = $Block; // 현재 블럭에 저장
                    //dump($CurrentBlock);

                    continue 2; //새로운 라인 시작
                }
            }

            //dd($CurrentBlock);

            // 마커에 블럭 검출하지 못함.
            # 블럭타입은 존재하나, type과 interrupted가 없는 경우 = 다음줄 추가
            if (isset($CurrentBlock) && 
                ! isset($CurrentBlock['type']) && 
                ! isset($CurrentBlock['interrupted'])) {
                
                $CurrentBlock['element']['text'] .= "\n".$Line['text'];
                
            } else {
                // 블록 목록에 저장
                $Blocks []= $CurrentBlock;

                //
                $CurrentBlock = $this->paragraph($Line);
                $CurrentBlock['identified'] = true;
                
            }
            //

        }

        //dump($CurrentBlock); 

        # ~
        if (isset($CurrentBlock['continuable']) && 
            $this->isBlockCompletable($CurrentBlock['type']))
        {
            $method = 'block'.$CurrentBlock['type'].'Complete';
            $CurrentBlock = $this->{$method}($CurrentBlock);
        }

        # ~
        $Blocks []= $CurrentBlock;
        unset($Blocks[0]);

        //dd($Blocks);
        return $Blocks;
    }



    // 텝을 공백으로 변환
    private function tabInLine($line)
    {
        if (strpos($line, "\t") !== false)
        {
            $parts = explode("\t", $line);

            // 0은 line으로 이동후에, 삭제...
            $line = $parts[0];
            unset($parts[0]);

            foreach ($parts as $part)
            {
                $shortage = 4 - mb_strlen($line, 'utf-8') % 4;
                $line .= str_repeat(' ', $shortage);
                $line .= $part;
            }
        }

        return $line;
    }



    # ~
    # 마크업 없는 블럭
    protected $unmarkedBlockTypes = ['Code'];
    private function blockTypes($marker)
    {
        $blockTypes = ['Code'];

        // 마커 찾기
        if (isset($this->BlockTypes[$marker])) {
            // 검출한 마커에 따른 블럭메소드 추가
            foreach ($this->BlockTypes[$marker] as $blockType)
            {
                $blockTypes []= $blockType;
            }
        }

        return $blockTypes;
    }



    private function blockRender($Blocks)
    {
        $markup = '';
        //dd($Blocks);
        foreach ($Blocks as $Block)
        {
            if (isset($Block['hidden']))
            {
                continue;
            }
            //
            $markup .= "\n"; ## 줄바꿈
            $markup .= isset($Block['markup']) ? $Block['markup'] : $this->element($Block['element']);
        }

        $markup .= "\n";
        return $markup;
    }

}
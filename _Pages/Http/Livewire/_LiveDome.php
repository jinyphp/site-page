<?php

namespace Jiny\Pages\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Pages\Http\Parsedown;

use \Jiny\Html\CTag;

class LiveDom extends Component
{
    private $i=0;
    private function transParser($body)
    {
        $body = str_replace(["\r\n", "\r"], "", $body);
        $body = str_replace("\n", "", $body);

        // 준비
        $obj = new CTag("div", true);
        $this->pos = strpos($body,"<");
        $obj->addItem(substr($body, 0, $this->pos));

        while($this->isStart($body)) {
            $arr = $this->getTag($body);
            $obj->addItem($arr);
        }

        return $obj;
    }


    private $pos=0;
    private function getTag($body)
    {
        // 시작테크 찾기
        $start = strpos($body,"<", $this->pos);
        $end = strpos($body,">",$start);
        $code = substr($body ,$start, $end-$start+1);


        $this->pos += strlen($code); 
        $tagName = explode(' ',str_replace(['<','>','/'],"",$code));
        if($tagName == "hr" || $tagName == "br" || $tagName == "img") {
            $obj = new CTag($tagName[0], false);
            return $obj;
        } else {
            $obj = new CTag($tagName[0], true);
        }

        
        while($this->isStart($body)) {
            $start = strpos($body,"<", $this->pos);
            if($block = substr($body,$this->pos,$start - $this->pos)) {
                $obj->addItem($block);
                $this->pos += strlen($block);
            }

            $arr = $this->getTag($body);
            $obj->addItem($arr);
            $arr = [];
        }


        $endTag = "</".$tagName[0].">";
        if ($end = strpos($body,$endTag,$this->pos)) {
            $block = substr($body,$this->pos, $end - $this->pos);        
            if($block) {
                $obj->addItem($block);
            }

            $this->pos = $end;
            $end = strpos($body,">",$this->pos);
            $close = substr($body, $this->pos, $end - $this->pos+1);
            $this->pos += strlen($close); //테그 크기많큼 증가
        }
        
        return $obj;
    }



    private function strpos($haystack, $needle, int $offset = 0){
        //if(empty($haystack)) return false;
        
        for($i=$offset, $j=0; $i<strlen($haystack); $i++) {
            if( $haystack[$i] == "\n") continue;
            if( $haystack[$i] == $needle[$j]) {
                if($j < strlen($needle)-1) {
                    $j++;
                } else 
                if($j == (strlen($needle)-1)) {
                    return $i - $j;
                }

                continue;
            } else {
                $j=0;
            }
            //dd($i);
        }
        return false;
    }

    private function substr(string $string, int $offset, $length = null) 
    {
        if($length) {
            $max = $offset + $length;
            if($max > strlen($string)) {
                $max = strlen($string);
            }
        } else {
            $max = strlen($string);
        }

        //dd($max);

        $text = "";
        //dump("offset=".$offset);
        for($i=$offset; $i<$max; $i++) {
            //dd($string[$i]);
            $text .= $string[$i];
        }

        //dump($offset."~".$length."=".$max);
        //dump($text);
        return $text;
    }

    public function isStart($body)
    {
        if($this->pos < strlen($body) ) {
            $i = strpos($body,"<",$this->pos);
            if ($i !== false) {
                if ($body[$i+1] == "/") {
                    //$this->level--;
                    return false;
                } else {
                    //$this->level++;
                    return true;
                }            
            }
        }
        
        return false;
    }

    /*

    private function parser()
    {
        $this->content = str_replace(array("\r\n", "\r"), "\n", $this->content);
        $lines = explode("\n", $this->content);
        
        foreach($lines as $line) {
            $key = $this->getKey($line);
            if($key == "#") {
                $html = $this->blockHeader(substr($line,1));
            } else 
            if($key == "|") { //테이블

            } else {
                $html = $line;
            }

            $this->markdown []= [
                'text' => $line, //원문
                'html' => $html  // 마크다운 변환
            ];
        }
    }

    private function blockHeader($line, $level=1)
    {
        $key = $this->getKey($line);
        if($key == "#" && $level<=6 ) {
            return $this->blockHeader(substr($line,1), ++$level);
        } else {
            return "<h".$level.">".$line."</h".$level.">";
        }        
    }

    private function getKey($line)
    {
        if(isset($line[0])) {
            return $line[0];
        }

        return '';
    }

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

    private function element($line)
    {
        $key = $line[0];
        if(isset($this->BlockTypes[$key])) {
            return $this->BlockTypes[$key];
        }        
    }
    */




}
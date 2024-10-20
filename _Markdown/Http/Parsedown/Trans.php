<?php
/**
 * Modified Parsedown
 */
namespace Modules\Markdown\Http\Parsedown;

use \Jiny\Html\CTag;

trait Trans
{
    public $admin = true;
    public $trans = true;
    public $transMode = true;
    public $message;
    public $language = "ko";

    private function trans_init()
    {
        // 번역지원 모드
        if(function_exists("polyglot")) {
            if($this->trans) {
                $path = resource_path('trans');
                $this->message = polyglot($path);
            }
        }
    }

    private function trans($text)
    {
        // 번역지원 모드
        if(function_exists("polyglot")) {
            if($this->trans && $text[0] !== "|") {
                $src = $text;
                $text = $this->message->echo($text, $this->language); // 번역

                // 관리자 : trans popup
                //dd($this->transMode);
                //if($this->transMode) {
                    $text = CDiv($text)
                        ->addClass('polyglot')
                        ->setAttribute('wire:click',"$"."emit('popupTransOpen','$src')")
                        //->setAttribute('wire:click',"popupTransOpen('$src')")
                        ->toString();
                //}

            }
            return $text;
        } else {
            return $text;
        }
    }
}

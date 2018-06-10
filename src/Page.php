<?php
namespace Jiny\Pages;

use Jiny\Core\Controllers\Controller;

/**
 * Core Controller를 상속받습니다.
 */
class PageController extends Controller
{
    // Application 인스턴스
    private $Application;

    public function __construct($app)
    {
        //echo __CLASS__." 객체를 생성하였습니다.<br>";
        // 의존성주입, 상위 Application의 객체를 저장합니다.
        $this->Application = $app;
    }

    /**
     * 페이지 기본 매서드
     */
    public function index()
    {
        //echo __METHOD__."를 호출합니다.<br>";        
        $pagepath = $this->pagePath();
        $indexpage = $pagepath."index";

        $data = [
            'name'=>$name,
            'id'=>$id
        ];

        // 뷰 객체를 생성합니다.
        $this->view($indexpage, $data);

        // 페이지를 처리
        $this->_view->create();

        // 화면출력
        $this->_view->show();
    }

    /**
     * 정적 페이지의 파일 경로를 재생성합니다.
     */
    public function pagePath()
    {
        foreach ($this->Application->_uri as $value) {
            $pagepath .= $value. DS;
        }

        return $pagepath;
    }

}
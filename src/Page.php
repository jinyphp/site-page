<?php
namespace Jiny\Pages;

use \Jiny\Core\Controllers\Controller;

/**
 * Core Controller를 상속받습니다.
 */
class Page extends Controller
{
    // Application 인스턴스
    private $Application;
    public $Menu;

    public $viewData;
    public $viewFile;

    public function __construct($app)
    {
        // echo __CLASS__." 객체를 생성하였습니다.<br>";
        // 의존성주입, 상위 Application의 객체를 저장합니다.
        $this->Application = $app;
    }

    /**
     * 페이지 기본 매서드
     */
    public function index()
    {
        //echo __METHOD__."를 호출합니다.<br>";
        // 처리될 view 페이지의 경로
        $this->viewFile = $this->getPath()."index";

        // view로 전달되는 데이터 array
        $this->viewData = [
            'name'=>$name,
            'id'=>$id
        ];

        // 메뉴 객체를 생성합니다.
        $this->Menu = new \Jiny\Menu\Menu($this->Application);
        $this->Application->Registry->set("Menu", $this->Menu);

        // 뷰 객체를 생성합니다.
        $this->viewFactory($this);

        // 뷰처리를 생성합니다.
        $this->View->create();

        // 화면출력
        $this->View->show();
    }

    /**
     * 정적 페이지의 파일 경로를 재생성합니다.
     */
    public function getPath()
    {
        foreach ($this->Application->_uri as $value) {
            $path .= $value. DS;
        }
        return $path;
    }

}
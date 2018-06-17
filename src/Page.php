<?php
namespace Jiny\Pages;

use \Jiny\Core\Controllers\Controller;

/**
 * Core Controller를 상속받습니다.
 */
class Page extends Controller
{
    /**
     * 인스턴스
     */
    public $Menu;

    public $viewData;
    public $viewFile;

    public function __construct($app=NULL)
    {
        // echo __CLASS__." 객체를 생성하였습니다.<br>";
        // 의존성주입, 상위 Application의 객체를 저장합니다.
        $this->setApp($app);
    }

    /**
     * 페이지 기본 매서드
     */
    public function index()
    {
        //echo __METHOD__."를 호출합니다.<br>";

        // 컨트롤러
        // 처리될 view 페이지의 경로
        $this->viewPath();

        // view로 전달되는 데이터 array
        /*
        $this->viewData['param'] = [
            'name'=>$name,
            'id'=>$id
        ];
        */

        // 메뉴를 생성합니다.
        // 인스턴스 Pool에 등록합니다.
        $this->Menu = new \Jiny\Menu\Menu($this->Application);
        $this->Application->Registry->set("Menu", $this->Menu);

        // 뷰 객체를 생성합니다.
        // 페지이는 뷰로 처리합니다.
        $this->viewFactory($this);

        // 뷰처리를 생성합니다.
        $this->View->create();

        // 화면출력
        $this->View->show();
    }

}
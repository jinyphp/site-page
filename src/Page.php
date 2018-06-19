<?php
namespace Jiny\Pages;

use \Jiny\Core\Controllers\Controller;

/**
 * jiny 
 * 페이지를 처리합니다. 페이지는 기본 컨트롤러 입니다.
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
        // \TimeLog::set(__CLASS__."가 생성이 되었습니다.");

        // 의존성 주입
        // 호출된 Application 클래스의 인스턴스르 저장합니다.

        $this->setApp($app);    //controller
    }

    /**
     * 기본 호출 메서드
     * Application 에서 호출시 선택되는 기본값 입니다.
     */
    public function index()
    {
        // \TimeLog::set(__METHOD__);

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

    /**
     * 페이지를 처리할 기본 경로를 반환합니다.
     */
    public function pagePath()
    {
        $this->viewFile = $this->getPath();
        return $this->viewFile;
    }

}
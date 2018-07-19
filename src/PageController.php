<?php
namespace Jiny\Pages;

use \Jiny\Core\Controllers\Controller;

/**
 * jiny 
 * 페이지를 처리합니다. 페이지는 기본 컨트롤러 입니다.
 * Core Controller를 상속받습니다.
 */
class PageController extends Controller
{
    /**
     * 인스턴스
     */
    public $viewData = [];
    public $viewFile;

    public function __construct($app=NULL)
    {
        \TimeLog::set(__CLASS__."가 생성이 되었습니다.");
        //echo "페이지 컨트롤러가 생성되었습니다.<br>";

        // 의존성 주입
        $this->setApp($app);
    }

    /**
     * 기본 호출 메서드
     * Application 에서 호출시 선택되는 기본값 입니다.
     */
    public function index()
    {
        \TimeLog::set(__METHOD__);
        // echo "페이지 컨트롤러<br>";

        if ($this->App->Route->_viewFile) {
            $viewFile = $this->App->Route->_viewFile;
        } else {
            // 처리될 페이지 경로
            $viewFile = $this->getPath();
        }

        // 메뉴 데이터를 읽어옵니다.
        $viewData['menus'] = menu();

        if ($ret = view($viewFile, $viewData)) {
            return $ret;
        } else {
            echo "404 페이지가 없습니다. from pages!";
        }

    }

}
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
        // 의존성 주입
        $this->setApp($app);
    }

    /**
     * 기본 호출 메서드
     * Application 에서 호출시 선택되는 기본값 입니다.
     */
    public function index()
    {

        if ($this->App->_viewFile) {
            // 라우터에 의해서 뷰파일이 지정된 경우
            $viewFile = $this->App->_viewFile;
        } else {
            // 리소스 페이지 결로파일.
            $viewFile = $this->getPath();
        }

        // 메뉴 데이터를 읽어옵니다.
        $viewData['menus'] = menu();
        if ($viewFile) {

            if ($screen = view($viewFile, $viewData)) {
                return $screen;
            } else {
                return $this->error_404("404 페이지가 없습니다. from pages!");
            }
        } else {
            return $this->error_404("viewFile 값이 없습니다.<br>");
        }


    }

    function error_404($msg)
    {
        // 메뉴 데이터를 읽어옵니다.
        $viewData['menus'] = menu();
        return view("/404", $viewData);
    }

}

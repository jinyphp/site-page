<?php
namespace Jiny\Pages;

use \Jiny\Core\Controllers\Controller;

/**
 * 
 */
class FormController extends Controller
{
    /**
     * 인스턴스
     */
    public $viewData;
    public $viewFile;

    public function __construct($app=NULL)
    {
        \TimeLog::set(__CLASS__."가 생성이 되었습니다.");

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

        return $this->view($viewFile);
    }

}
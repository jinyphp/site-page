<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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


    /**
     *  초기화, 의존성 주입 
     */
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

    }

    /**
     * 
     */
}
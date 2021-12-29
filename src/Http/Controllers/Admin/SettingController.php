<?php

namespace Jiny\Pages\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Jiny\Table\Http\Controllers\ConfigController;
class SettingController extends ConfigController
{
    const MENU_PATH = "menus";
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        ##
        $this->actions['filename'] = "jiny_pages"; // 파일명

        //$this->actions['view_main'] = "jinytable::admin.setting.main";
        //$this->actions['view_title'] = "jinytable::admin.setting.title";
        // $this->actions['view_list'] = "jinytable::admin.setting.list";
        $this->actions['view_form'] = "jinypage::admin.setting.form";


        // 메뉴 설정
        $user = Auth::user();
        if(isset($user->menu)) {
            ## 사용자 지정메뉴 우선설정
            xMenu()->setPath($user->menu);
        } else {
            ## 설정에서 적용한 메뉴
            if(isset($this->actions['menu'])) {
                $menuid = _getKey($this->actions['menu']);
                xMenu()->setPath(self::MENU_PATH.DIRECTORY_SEPARATOR.$menuid.".json");
            }
        }
    }



}

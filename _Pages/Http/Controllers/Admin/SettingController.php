<?php

namespace Modules\Pages\Http\Controllers\Admin;

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
        $this->actions['view_form'] = "pages::admin.setting.form";

        // 테마설정
        setTheme("admin/sidebar");
    }

}

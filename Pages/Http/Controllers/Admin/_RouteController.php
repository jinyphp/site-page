<?php

namespace Jiny\Pages\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Jiny\Table\Http\Controllers\ResourceController;
class RouteController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);


        ## 테이블 정보
        $this->actions['table'] = "site_route";

        $this->actions['view_filter'] = "pages::admin.route.filter";
        $this->actions['view_list'] = "pages::admin.route.list";
        $this->actions['view_form'] = "pages::admin.route.form";


        // 메뉴 설정
        $user = Auth::user();
        if(isset($user->menu)) {
            ## 사용자 지정메뉴 우선설정
            xMenu()->setPath($user->menu);
        } else {
            if(isset($this->actions['menu'])) {
                xMenu()->setPath($this->actions['menu']);
            }
        }
    }




}

<?php

namespace Jiny\Pages\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Jiny\Table\Http\Controllers\ResourceController;
class PostsController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);


        ## 테이블 정보
        $this->actions['table'] = "site_posts";

        $this->actions['view_filter'] = "jinypage::admin.posts.filter";
        $this->actions['view_list'] = "jinypage::admin.posts.list";
        $this->actions['view_form'] = "jinypage::admin.posts.form";


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


    public function hookDeleting($row)
    {

        return $row;
    }

}

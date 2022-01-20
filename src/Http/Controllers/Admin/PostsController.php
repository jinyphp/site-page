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

    }

}

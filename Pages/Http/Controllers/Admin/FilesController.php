<?php

namespace Modules\Pages\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Jiny\Table\Http\Controllers\ResourceController;
class FilesController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);


        ## 테이블 정보
        //$this->actions['table'] = "site_posts";

        $this->actions['view_main'] = "pages::admin.lists.main";

        //$this->actions['view_filter'] = "jinypage::admin.posts.filter";
        $this->actions['view_list'] = "pages::admin.lists.list";
        $this->actions['view_form'] = "pages::admin.lists.form";

        $this->actions['file_path'] = resource_path("views");


        // 테마설정
        setTheme("admin/sidebar");
    }

}

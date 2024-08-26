<?php
namespace Jiny\Site\Page\Http\Controllers\Admin;

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

        $this->actions['view_filter'] = "jiny-site-page::admin.posts.filter";
        $this->actions['view_list'] = "jiny-site-page::admin.posts.list";
        $this->actions['view_form'] = "jiny-site-page::admin.posts.form";

    }

}

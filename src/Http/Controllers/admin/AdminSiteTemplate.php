<?php
namespace Jiny\Site\Page\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Jiny\WireTable\Http\Controllers\WireDashController;
class AdminSiteTemplate extends WireDashController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        $this->actions['view']['layout'] = "jiny-site-page::admin.template.layout";

        $this->actions['title'] = "Site Template";
        $this->actions['subtitle'] = "사이트 요소구성을 위한 템플릿을 관리합니다.";

        //setMenu('menus/site.json');
        // setTheme("admin.hyper");
    }


    public function index(Request $request)
    {
        $data = [
            'actions' => $this->actions,
        ];

        $viewFile = $this->getViewFileLayout($default=null);
        return view($viewFile,$data);
        // return parent::index($request);
    }

}

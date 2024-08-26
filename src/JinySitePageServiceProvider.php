<?php
namespace Jiny\Site\Page;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Illuminate\Support\Facades\View;

class JinySitePageServiceProvider extends ServiceProvider
{
    private $package = "jiny-site-page";

    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/404.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');


        /* 컴포넌트 클래스 등록 */
        $this->loadViewComponentsAs($this->package, [

        ]);

        ## 테마를 선택하고 app과 컨덴츠를 결합합니다.
        Blade::component(\Jiny\Site\Page\View\Components\Markdown::class, "markdown");

    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            Livewire::component('PageContextPopup', \Jiny\Site\Page\Http\Livewire\PageContents::class);

            Livewire::component('LiveMarkdown', \Jiny\Site\Page\Http\Livewire\LiveMarkdown::class);
            Livewire::component('LiveTrans', \Jiny\Site\Page\Http\Livewire\LiveTrans::class);

            Livewire::component('setPageRule', \Jiny\Site\Page\Http\Livewire\SetPageRule::class);
            Livewire::component('setMarkRule', \Jiny\Site\Page\Http\Livewire\SetMarkRule::class);
            Livewire::component('setPostRule', \Jiny\Site\Page\Http\Livewire\SetPostRule::class);


        });
    }



}

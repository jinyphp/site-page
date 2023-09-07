<?php

namespace Jiny\Pages;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

use Illuminate\Support\Facades\View;

class JinyPageServiceProvider extends ServiceProvider
{
    private $package = "jinypage";
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
        Blade::component(\Jiny\Pages\View\Components\Markdown::class, "markdown");

    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            Livewire::component('PageContextPopup', \Jiny\Pages\Http\Livewire\PageContents::class);

            Livewire::component('LiveMarkdown', \Jiny\Pages\Http\Livewire\LiveMarkdown::class);
            Livewire::component('LiveTrans', \Jiny\Pages\Http\Livewire\LiveTrans::class);

            Livewire::component('setPageRule', \Jiny\Pages\Http\Livewire\SetPageRule::class);
            Livewire::component('setMarkRule', \Jiny\Pages\Http\Livewire\SetMarkRule::class);
            Livewire::component('setPostRule', \Jiny\Pages\Http\Livewire\SetPostRule::class);


        });
    }



}

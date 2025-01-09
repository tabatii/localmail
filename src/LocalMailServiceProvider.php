<?php

namespace Tabatii\LocalMail;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Illuminate\Routing\Router;
use Livewire\LivewireManager;

class LocalMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/localmail.php', 'localmail');
        $this->app->bind('localmail', fn () => new LocalMail());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->make('config')->get('localmail.enabled', true)) {
            $this->registerMailer();
            $this->registerRoutes();
            $this->registerComponents();
            $this->registerViews();
            $this->registerMigrations();
            $this->registerCommands();
            $this->registerPublishing();
        }
    }

    protected function registerMailer(): void
    {
        $this->callAfterResolving('mail.manager', function (MailManager $mail) {
            $mail->extend('localmail', fn (array $config = []) => new LocalMailTransport);
        });
    }

    protected function registerRoutes(): void
    {
        $this->callAfterResolving('router', function (Router $router, Application $app) {
            $router
                ->prefix($app->make('config')->get('localmail.routes.prefix', 'localmail'))
                ->middleware($app->make('config')->get('localmail.routes.middleware', ['web']))
                ->group(function (Router $router) {
                    $router->get('/', Livewire\Dashboard::class)->name('localmail.dashboard');
                    $router->get('recipient/{id}', Livewire\Recipient::class)->name('localmail.recipient');
                    $router->get('message/{id}', Livewire\Message::class)->name('localmail.message');
                });
        });
    }

    protected function registerComponents(): void
    {
        $this->callAfterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->anonymousComponentPath(__DIR__.'/../resources/views/components', 'localmail');
        });
        $this->callAfterResolving('livewire', function (LivewireManager $livewire) {
            $livewire->component('localmail.dashboard', Livewire\Dashboard::class);
            $livewire->component('localmail.recipient', Livewire\Recipient::class);
            $livewire->component('localmail.message', Livewire\Message::class);
            $livewire->component('localmail.sidebar', Livewire\Sidebar::class);
        });
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'localmail');
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\SendFakeEmail::class,
            ]);
        }
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/localmail.php' => config_path('localmail.php'),
            ], 'localmail-config');
        }
    }
}

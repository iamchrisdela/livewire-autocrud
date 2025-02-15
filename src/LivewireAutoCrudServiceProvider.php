<?php

namespace Iamchris\LivewireAutocrud;

use iamchris\LivewireAutoCrud\Console\Commands\MakeLivewireAutoCrudCommand;
use Illuminate\Support\ServiceProvider;

class LivewireAutoCrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeLivewireAutoCrudCommand::class,
            ]);
        }
    }

    public function register()
    {
        // Register any bindings or services here
    }
}

<?php

namespace Iamchris\LivewireAutocrud;

use iamchris\LivewireAutoCrud\Console\Commands\MakeLivewireCrudCommand;
use Illuminate\Support\ServiceProvider;

class LivewireCrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeLivewireCrudCommand::class,
            ]);
        }
    }

    public function register()
    {
        // Register any bindings or services here
    }
}

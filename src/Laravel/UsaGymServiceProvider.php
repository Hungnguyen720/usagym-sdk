<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Laravel;

use AustinW\UsaGym\Laravel\Console\UsaGymTestCommand;
use AustinW\UsaGym\UsaGym;
use Illuminate\Support\ServiceProvider;

class UsaGymServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/usagym.php', 'usagym');

        $this->app->singleton(UsaGym::class, function ($app) {
            $config = $app['config']['usagym'];

            return new UsaGym(
                username: $config['username'] ?? '',
                password: $config['password'] ?? '',
            );
        });

        $this->app->alias(UsaGym::class, 'usagym');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/usagym.php' => config_path('usagym.php'),
            ], 'usagym-config');

            $this->commands([
                UsaGymTestCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            UsaGym::class,
            'usagym',
        ];
    }
}

<?php namespace Netfizz\Generators;

use Netfizz\Generators\Commands;
use Way\Generators\Generators;
use Way\Generators\Cache;
use Illuminate\Support\ServiceProvider;

use Way\Generators\GeneratorsServiceProvider as WayGeneratorsServiceProvider;


class GeneratorsServiceProvider extends WayGeneratorsServiceProvider {
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('netfizz/generators');
	}

    /**
     * Register generate:migration
     *
     * @return Commands\MigrationGeneratorCommand
     */
    protected function registerMigrationGenerator()
    {
        $this->app['generate.migration'] = $this->app->share(function($app)
        {
            $cache = new Cache($app['files']);
            $generator = new Generators\MigrationGenerator($app['files'], $cache);

            return new Commands\MigrationGeneratorCommand($generator);
        });
    }

}
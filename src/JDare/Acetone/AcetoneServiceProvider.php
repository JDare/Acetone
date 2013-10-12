<?php

namespace JDare\Acetone;

use Illuminate\Support\ServiceProvider;

class AcetoneServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('jdare/acetone');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Acetone', 'JDare\Acetone\Facades\Acetone');
        });

        $this->app['acetone'] = $this->app->share(function($app)
        {
            return new Acetone;
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array("acetone");
	}

}
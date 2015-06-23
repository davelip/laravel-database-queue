<?php namespace Davelip\Queue;

use Illuminate\Support\ServiceProvider;
use Davelip\Queue\Connectors\DatabaseConnector;
use Davelip\Queue\Console\DatabaseCommand;

class DatabaseServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDatabaseCommand();
    }

    /**
     * Add the connector to the queue drivers
     */
    public function boot()
    {
        $manager = $this->app['queue'];
        $this->registerDatabaseConnector($manager);
    }

    /**
     * Register the queue listener console command.
     *
     * @return void
     */
    protected function registerDatabaseCommand()
    {
        $app = $this->app;

        $app['command.queue.database'] = $app->share(function ($app) {
                return new DatabaseCommand();
            });

        $this->commands('command.queue.database');
    }

    /**
     * Register the Database queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager $manager
     * @return void
     */
    protected function registerDatabaseConnector($manager)
    {
        $manager->addConnector('database', function () {
                return new DatabaseConnector($this->app['db']);
            });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('command.queue.database');
    }
}

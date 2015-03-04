# Laravel 4 Database Queue Driver

## Push a function/closure to the Database queue.
This is a real queue driver, like beanstalkd or redis one.
You need a daemon like supervisor or similar to listen to your queue.

### Install
Add the package to the require section of your composer.json and run `composer update`

    "davelip/laravel-database-queue": "0.1.x"

Add the Service Provider to the providers array in config/app.php

    'Davelip\Queue\DatabaseServiceProvider',
    
I suggest to publish migrations, so they are copied to your regular migrations

    $ php artisan migrate:publish davelip/laravel-database-queue

And then run migrate 

    $ php artisan migrate 

You should now be able to use the database driver in config/queue.php

    'default' => 'database',
    
    'connections' => array(
        ...
        'database' => array(
            'driver' => 'database',
            'queue' => 'queue-name',
        ),
        ...
    }

It work in the same as beanstalkd or redis queue listener.

Listen for new job:

    $ php artisan queue:listen


### Laravel Queue System
For more info see http://laravel.com/docs/queues

#### Thanks
Loosely based on https://github.com/barryvdh/laravel-async-queue

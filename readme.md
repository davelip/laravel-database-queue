# Laravel 4 Database Queue Driver

## Push a function/closure to the Database queue.
This is a real queue driver, like beanstalkd or redis one.
You need a daemon like supervisor or similar to listen to your queue.

### Install
Add the package to the require section of your composer.json and run `composer update`

    "davelip/laravel-database-queue": ">0.5"

Add the Service Provider to the providers array in config/app.php

    'Davelip\Queue\DatabaseServiceProvider'

I suggest to publish migrations, so they are copied to your regular migrations

    $ php artisan migrate:publish davelip/laravel-database-queue

And then run migrate 

    $ php artisan migrate 

I suggest to create the `failed_jobs` table, in this moment, with:

    $ php artisan queue:failed-table

You should now be able to use the database driver in config/queue.php

    'default' => 'database',
    
    'connections' => array(
        ...
        'database' => array(
            'driver' => 'database',
            'queue' => 'queue-name', // optional, can be null or any string
            'lock_type' => 0, // optional, can be 0, 1 or 2
        ),
        ...
    }

It work in the same as beanstalkd or redis queue listener.

Listen for new job:

    $ php artisan queue:listen


Concurrency are managed by `status` column in the `queues` table, so you can parallelize your `queue:listen`.

Atomicity of status change are garantee by database transaction, if you are having problems of race condition 
can You set the option `lock_type` to:

 * 'lock_type' => 1 // queue system will use sharedLock 
 * 'lock_type' => 2 // queue system will use lockForUpdate 

see http://laravel.com/docs/4.2/queries#pessimistic-locking for further info.


### Laravel Queue System
For more info see http://laravel.com/docs/queues

#### Thanks
Loosely based on https://github.com/barryvdh/laravel-async-queue

<?php namespace Davelip\Queue\Console;

use Davelip\Queue\Models\Job;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Davelip\Queue\Jobs\DatabaseJob;

class DatabaseCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a queue from the database';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $item = Job::findOrFail($this->argument('job_id'));

        $job = new DatabaseJob($this->laravel, $item);

        $job->fire();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('job_id', InputArgument::REQUIRED, 'The Job ID'),
        );
    }
}

<?php namespace Davelip\Queue\Jobs;

use Carbon\Carbon;
use Davelip\Queue\Models\Job;
use Illuminate\Container\Container;

class DatabaseJob extends \Illuminate\Queue\Jobs\Job
{
    /**
     * The job model
     *
     * @var Job
     */
    protected $job;

    /**
     * job name
     *
     * @var string
     */
    protected $name;

    /**
     * True if the job was released back onto the Queue
     *
     * @var bool
     */
    protected $released;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Davelip\Queue\Models\Job       $job
     * @param string                          $queue
     */
    public function __construct(Container $container, Job $job, $queue)
    {
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        // Get the payload from the job
        $payload = $this->parsePayload($this->job->payload);
        $this->name = $payload['job'];

        // Fire the actual job
        $this->resolveAndFire($payload);

        /*
         * As per l5 the documentation states that a job which has not thrown any exception by the time it finishes will
         * be deleted.
         */
        // If job is not deleted, and was not released back onto the queue, mark as finished
        if (! $this->deleted && !$this->released) {
            $this->job->status = Job::STATUS_FINISHED;
            $this->job->save();
        }
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->job->delete();
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int  $delay
     * @return void
     */
    public function release($delay = 0)
    {
        $now = new Carbon();
        $now->addSeconds($delay);
        $this->job->timestamp = $now->toDateTimeString();
        $this->job->status = Job::STATUS_WAITING;
        $this->job->retries += 1;
        $this->job->save();
        $this->released = true;
    }

    /**
     * Parse the payload to an array.
     *
     * @param $payload
     *
     * @return array|null
     */
    protected function parsePayload($payload)
    {
        return json_decode($payload, true);
    }

    /**
     * Get the name of the queued job class.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the number of attempts this task has been done
     *
     * @return int
     */
    public function attempts()
    {
        return ($this->job->retries + 1);
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->getKey();
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        // FIXME: This is a best guess, other implementations are used like this:  $this->resolveAndFire(json_decode($this->getRawBody(), true));

        return $this->job->payload;
    }
}

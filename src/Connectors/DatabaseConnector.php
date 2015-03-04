<?php namespace Davelip\Queue\Connectors;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Davelip\Queue\DatabaseQueue;

class DatabaseConnector implements ConnectorInterface {

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Queue\QueueInterface
     */
    public function connect(array $config)
    {
        return new DatabaseQueue(array_get($config, 'queue'));
    }

}

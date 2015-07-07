<?php namespace Davelip\Queue\Connectors;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Davelip\Queue\DatabaseQueue;
use Illuminate\Database\ConnectionResolverInterface;

class DatabaseConnector implements ConnectorInterface
{
    /**
     * Database connections.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $connections;

    /**
     * Create a new connector instance.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $connections
     * @return void
     */
    public function __construct(ConnectionResolverInterface $connections)
    {
        $this->connections = $connections;
    }

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new DatabaseQueue(
            $this->connections->connection(array_get($config, 'connection')),
            array_get($config, 'table', 'queues'),
            array_get($config, 'queue', null),
            array_get($config, 'expire', 60),
            array_get($config, 'lock_type', 60)
        );
    }
}

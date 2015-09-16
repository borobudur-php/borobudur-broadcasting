<?php
/*
 * This file is part of the Borobudur-Broadcasting package.
 *
 * (c) Hexacodelabs <http://hexacodelabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Borobudur\Broadcasting\Broadcaster;

use Borobudur\Broadcasting\BroadcastEventInterface;
use Redis;

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     9/16/15
 */
class RedisBroadcaster implements BroadcasterInterface
{
    const BROADCASTER_NAME = 'redis';

    /**
     * @var Redis
     */
    private $redis;

    /**
     * Constructor.
     *
     * @param string      $host
     * @param int         $port
     * @param int         $db
     * @param string|null $password
     * @param int         $timeout
     */
    public function __construct($host = '127.0.0.1', $port = 6379, $db = 0, $password = null, $timeout = 0)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port, $timeout);

        if (null !== $password) {
            $this->redis->auth($password);
        }

        $this->redis->select($db);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return RedisBroadcaster::BROADCASTER_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(BroadcastEventInterface $event)
    {
        $payload = json_encode(array('event' => $event->getBroadcastName(), 'data' => $event->getBroadcastPayload()));

        foreach ($event->getBroadcastChannels() as $channel) {
            $this->redis->publish($channel, $payload);
        }
    }
}

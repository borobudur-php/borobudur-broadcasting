<?php
/*
 * This file is part of the Borobudur-Broadcasting package.
 *
 * (c) Hexacodelabs <http://hexacodelabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Borobudur\Broadcasting;

use Borobudur\Broadcasting\Broadcaster\BroadcasterInterface;
use Borobudur\Broadcasting\Exception\InvalidArgumentException;
use Borobudur\Broadcasting\Exception\RuntimeException;

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     9/16/15
 */
class Broadcaster
{
    /**
     * @var Broadcaster
     */
    private static $instance;

    /**
     * @var BroadcasterInterface[]
     */
    private $broadcasters = array();

    /**
     * Constructor.
     *
     * @param BroadcasterInterface[] $broadcasters
     */
    public function __construct(array $broadcasters = array())
    {
        foreach ($broadcasters as $broadcaster) {
            $this->add($broadcaster);
        }

        self::$instance = $this;
    }

    /**
     * Get last built instance.
     *
     * @return Broadcaster
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            throw new RuntimeException('Broadcaster is not instantiated yet.');
        }

        return self::$instance;
    }

    /**
     * Register a broadcaster.
     *
     * @param BroadcasterInterface $broadcaster
     */
    public function add(BroadcasterInterface $broadcaster)
    {
        if ($this->has($broadcaster->getName())) {
            throw new InvalidArgumentException(sprintf(
                'Broadcaster with name "%s" already registered.',
                $broadcaster->getName()
            ));
        }

        $this->broadcasters[$broadcaster->getName()] = $broadcaster;
    }

    /**
     * Check if broadcaster has been registered or not.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->broadcasters[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(BroadcastEventInterface $event)
    {
        foreach ($this->broadcasters as $broadcaster) {
            $broadcaster->broadcast($event);
        }
    }
}

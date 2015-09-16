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

use Borobudur\Broadcasting\Exception\InvalidArgumentException;
use Borobudur\Broadcasting\Exception\RuntimeException;
use ReflectionClass;

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     9/16/15
 */
class BroadcasterManager
{
    /**
     * @var BroadcasterInterface[]|null
     */
    private static $broadcasters = null;

    /**
     * @var BroadcasterInterface[]|null
     */
    private static $instantiated = array();

    /**
     * Private constructor.
     */
    private function __construct()
    {
        // Do nothing.
    }

    /**
     * Add a broadcaster.
     *
     * @param BroadcasterInterface $broadcaster
     */
    public static function addBroadcaster(BroadcasterInterface $broadcaster)
    {
        self::instantiateDefaultBroadcasters();
        self::$broadcasters[$broadcaster->getName()] = $broadcaster;
    }

    /**
     * Get instantiated broadcaster.
     *
     * @param string $name
     *
     * @return BroadcasterInterface
     */
    public static function get($name)
    {
        $name = strtolower($name);

        if (!isset(self::$instantiated[$name])) {
            throw new InvalidArgumentException(sprintf('Broadcaster "%s" is not instantiated.', $name));
        }

        return self::$instantiated[$name];
    }

    /**
     * Build a broadcaster.
     *
     * @param string $name
     * @param array  $configs
     *
     * @return BroadcasterInterface
     */
    public static function build($name, array $configs = array())
    {
        if (isset(self::$instantiated[$name])) {
            return self::$instantiated[$name];
        }

        self::instantiateDefaultBroadcasters();
        if (!isset(self::$broadcasters[$name])) {
            throw new InvalidArgumentException(sprintf('Undefined broadcaster with name "%s".', $name));
        }

        self::$instantiated[$name] = self::instantiate(self::$broadcasters[$name], $configs);

        return self::$instantiated[$name];
    }

    /**
     * Instantiate broadcaster.
     *
     * @param string $class
     * @param array  $configs
     *
     * @return BroadcasterInterface
     */
    private static function instantiate($class, array $configs)
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        $arguments = array();

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            if (isset($configs[$name])) {
                $arguments[] = $configs[$name];
                continue;
            }

            $arguments[] = null;
        }

        $broadcaster = $reflection->newInstanceArgs($arguments);

        if (!$broadcaster instanceof BroadcasterInterface) {
            throw new RuntimeException(sprintf(
                'Broadcaster "%s" should implement Borobudur\Broadcasting\Broadcaster\BroadcasterInterface',
                $class
            ));
        }

        return $broadcaster;
    }

    /**
     * Get default broadcasters.
     *
     * @return BroadcasterInterface[]
     */
    private static function instantiateDefaultBroadcasters()
    {
        if (null === self::$broadcasters) {
            self::$broadcasters = array(
                RedisBroadcaster::BROADCASTER_NAME => '\Borobudur\Broadcasting\Broadcaster\RedisBroadcaster',
            );
        }
    }
}

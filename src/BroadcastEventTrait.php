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

use ReflectionObject;
use ReflectionProperty;

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     9/16/15
 */
trait BroadcastEventTrait
{
    /**
     * @var string
     * @internal
     */
    private $broadcastName;

    /**
     * @var array
     * @internal
     */
    private $broadcastChannels;

    /**
     * Set broadcast name.
     *
     * @param string $name
     */
    public function setBroadcastName($name)
    {
        $this->broadcastName = $name;
    }

    /**
     * Get broadcast name.
     *
     * @return string
     */
    public function getBroadcastName()
    {
        if (null === $this->broadcastName) {
            return get_called_class();
        }

        return $this->broadcastName;
    }

    /**
     * Set broadcast channel.
     *
     * @param array $channels
     */
    public function setBroadcastChannel(array $channels)
    {
        $this->broadcastChannels = $channels;
    }

    /**
     * Get broadcast channel.
     *
     * @return array
     */
    public function getBroadcastChannels()
    {
        if (null === $this->broadcastChannels) {
            return array(BroadcastEventInterface::DEFAULT_CHANNEL);
        }

        return $this->broadcastChannels;
    }

    /**
     * @return array|null
     */
    public function getBroadcastPayload()
    {
        if (!method_exists($this, 'serialize')) {
            $reflection = new ReflectionObject($this);
            $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
            $data = array();

            foreach ($properties as $prop) {
                $data[$prop->getName()] = $prop->getValue($this);
            }

            return $data;
        }

        return $this->{'serialize'}();
    }
}

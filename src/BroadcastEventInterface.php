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

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     9/16/15
 */
interface BroadcastEventInterface
{
    const DEFAULT_CHANNEL = 'borobudur-channel';

    /**
     * Set broadcast name.
     *
     * @param string $name
     */
    public function setBroadcastName($name);

    /**
     * Get broadcast name.
     *
     * @return string
     */
    public function getBroadcastName();

    /**
     * Set broadcast channels.
     *
     * @param array $channels
     */
    public function setBroadcastChannel(array $channels);

    /**
     * Get broadcast channels.
     *
     * @return array
     */
    public function getBroadcastChannels();

    /**
     * @return array|null
     */
    public function getBroadcastPayload();
}

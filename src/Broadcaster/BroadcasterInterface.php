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

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     9/16/15
 */
interface BroadcasterInterface
{
    /**
     * Broadcaster name.
     *
     * @return string
     */
    public function getName();

    /**
     * Broadcast the event.
     *
     * @param BroadcastEventInterface $event
     */
    public function broadcast(BroadcastEventInterface $event);
}

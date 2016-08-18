<?php

namespace Martin1982\LiveBroadcastBundle\Broadcaster;

use Martin1982\LiveBroadcastBundle\Entity\Channel\BaseChannel;
use Martin1982\LiveBroadcastBundle\Entity\LiveBroadcast;

/**
 * Class RunningBroadcast.
 */
class RunningBroadcast
{
    /**
     * @var int
     */
    private $broadcastId;

    /**
     * @var int
     */
    private $processId;

    /**
     * @var int
     */
    private $channelId;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $isMonitor;

    /**
     * RunningBroadcast constructor.
     *
     * @param int $broadcastId
     * @param int $processId
     * @param int $channelId
     * @param string $environment
     * @param bool $isMonitor
     */
    public function __construct($broadcastId, $processId, $channelId, $environment, $isMonitor = false)
    {
        $this->broadcastId = (int) $broadcastId;
        $this->processId = (int) $processId;
        $this->channelId = (int) $channelId;
        $this->environment = $environment;
        $this->isMonitor = $isMonitor;
    }

    /**
     * @return int
     */
    public function getBroadcastId()
    {
        return $this->broadcastId;
    }

    /**
     * @return int
     */
    public function getProcessId()
    {
        return $this->processId;
    }

    /**
     * @return int
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return bool
     */
    public function isMonitor()
    {
        return $this->isMonitor;
    }

    /**
     * @param $broadcast
     * @param $channel
     * @return bool
     */
    public function isBroadcasting(LiveBroadcast $broadcast, BaseChannel $channel)
    {
        return ($this->broadcastId === $broadcast->getBroadcastId()) && ($this->channelId === $channel->getChannelId());
    }

    /**
     * @param string $kernelEnvironment
     *
     * @return bool
     */
    public function isValid($kernelEnvironment)
    {
        return ($kernelEnvironment === $this->getEnvironment()) &&
            ($this->getProcessId() !== 0) &&
            ($this->getBroadcastId() !== 0) &&
            ($this->getChannelId() !== 0);
    }
}

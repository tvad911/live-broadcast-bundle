<?php

namespace Martin1982\LiveBroadcastBundle\Streams\Input;

use Martin1982\LiveBroadcastBundle\Entity\Input\InputRtmp;
use Martin1982\LiveBroadcastBundle\Entity\LiveBroadcast;

/**
 * Class Rtmp.
 */
class Rtmp implements InputInterface
{
    const INPUT_TYPE = 'rtmp';

    /** @var  LiveBroadcast */
    protected $broadcast;

    /**
     * Rtmp constructor.
     */
    public function __construct(LiveBroadcast $broadcast)
    {
        /** @var InputRtmp $inputEntity */
        $inputEntity = $broadcast->getInput();
        $streamKey = $inputEntity->getStreamKey();

        if (empty($streamKey)) {
            throw new LiveBroadcastException(sprintf('No stream key given for %s', $inputEntity->getIssuedTo()));
        }

        $this->ensureRedAcceptsConnection();
        $this->broadcast = $broadcast;
    }

    /**
     * @return string
     */
    public function generateInputCmd()
    {
        // TODO: Implement generateInputCmd() method.
    }

    protected function ensureRedAcceptsConnection()
    {
        throw new \Exception('No implementation yet');
    }
}

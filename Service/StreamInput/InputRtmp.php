<?php

namespace Martin1982\LiveBroadcastBundle\Service\StreamInput;

use Martin1982\LiveBroadcastBundle\Entity\Media\BaseMedia;
use Martin1982\LiveBroadcastBundle\Entity\Media\MediaRtmp;
use Martin1982\LiveBroadcastBundle\Entity\LiveBroadcast;
use Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastInputException;

/**
 * Class Rtmp.
 */
class Rtmp implements InputInterface
{
    /**
     * @var MediaRtmp
     */
    private $media;

    /**
     * {@inheritdoc}
     */
    public function setMedia(BaseMedia $media)
    {
        $this->media = $media;

        return $this;
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

    /**
     * @return MediaRtmp
     */
    public function getMediaType()
    {
        return MediaRtmp::class;
    }
}

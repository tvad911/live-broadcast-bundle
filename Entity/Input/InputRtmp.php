<?php

namespace Martin1982\LiveBroadcastBundle\Entity\Input;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InputRtmp.
 *
 * @ORM\Table(name="broadcast_input_rtmp", options={"collate"="utf8mb4_general_ci", "charset"="utf8mb4"})
 * @ORM\Entity()
 */
class InputRtmp extends BaseInput
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="stream_key", type="string", length=128, nullable=false)
     */
    protected $streamKey;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="issued_to", type="string", length=128, nullable=false)
     */
    protected $issuedTo;

    /**
     * @return string
     */
    public function getStreamKey()
    {
        return $this->streamKey;
    }

    /**
     * @param string $streamKey
     *
     * @return InputRtmp
     */
    public function setStreamKey($streamKey)
    {
        $this->streamKey = $streamKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIssuedTo()
    {
        return $this->issuedTo;
    }

    /**
     * @param mixed $issuedTo
     * @return InputRtmp
     */
    public function setIssuedTo($issuedTo)
    {
        $this->issuedTo = $issuedTo;

        return $this;
    }

    /**
     * Get input string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Live by ' . $this->getIssuedTo();
    }
}

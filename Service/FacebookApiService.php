<?php

namespace Martin1982\LiveBroadcastBundle\Service;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook as FacebookSDK;
use Martin1982\LiveBroadcastBundle\Entity\LiveBroadcast;
use Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastOutputException;
use Martin1982\LiveBroadcastBundle\Service\StreamOutput\OutputFacebook;

/**
 * Class FacebookApiService
 * @package Martin1982\LiveBroadcastBundle\Service
 */
class FacebookApiService
{
    /**
     * @var string
     */
    private $applicationId;

    /**
     * @var string
     */
    private $applicationSecret;

    /**
     * @var FacebookSDK
     */
    private $facebookSDK;

    /**
     * FacebookApiService constructor.
     * @param string $applicationId
     * @param string $applicationSecret
     * @throws LiveBroadcastOutputException
     */
    public function __construct($applicationId, $applicationSecret)
    {
        $this->applicationId = $applicationId;
        $this->applicationSecret = $applicationSecret;
    }

    /**
     * @param LiveBroadcast  $liveBroadcast
     * @param OutputFacebook $outputFacebook
     * @return null|string
     * @throws LiveBroadcastOutputException
     */
    public function createFacebookLiveVideo(LiveBroadcast $liveBroadcast, OutputFacebook $outputFacebook)
    {
        if (!$this->facebookSDK) {
            $this->initFacebook();
        }

        try {
            $params = array('title' => $liveBroadcast->getName(),
                            'description' => $liveBroadcast->getDescription());

            $this->facebookSDK->setDefaultAccessToken($outputFacebook->getAccessToken());
            $response = $this->facebookSDK->post($outputFacebook->getEntityId().'/live_videos', $params);
        } catch (FacebookResponseException $ex) {
            throw new LiveBroadcastOutputException('Facebook exception: '.$ex->getMessage());
        } catch (FacebookSDKException $ex) {
            throw new LiveBroadcastOutputException('Facebook SDK exception: '.$ex->getMessage());
        }

        $body = $response->getDecodedBody();

        if (array_key_exists('stream_url', $body)) {
            return $body['stream_url'];
        }

        return null;
    }

    /**
     * @param string $userAccessToken
     * @return \Facebook\Authentication\AccessToken|null
     * @throws LiveBroadcastOutputException
     */
    public function getLongLivedAccessToken($userAccessToken)
    {
        if (!$this->facebookSDK) {
            $this->initFacebook();
        }

        if (!$userAccessToken) {
            return null;
        }

        try {
            return $this->facebookSDK->getOAuth2Client()->getLongLivedAccessToken($userAccessToken);
        } catch (FacebookSDKException $ex) {
            throw new LiveBroadcastOutputException('Facebook SDK exception: '.$ex->getMessage());
        }
    }

    /**
     * @throws LiveBroadcastOutputException
     */
    private function initFacebook()
    {
        if (empty($this->applicationId) || empty($this->applicationSecret)) {
            throw new LiveBroadcastOutputException('The Facebook application settings are not correct.');
        }

        try {
            $this->facebookSDK = new FacebookSDK([
                'app_id' => $this->applicationId,
                'app_secret' => $this->applicationSecret,
            ]);
        } catch (FacebookSDKException $ex) {
            throw new LiveBroadcastOutputException('Facebook SDK Exception: '.$ex->getMessage());
        }
    }
}

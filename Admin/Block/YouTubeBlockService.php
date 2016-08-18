<?php

namespace Martin1982\LiveBroadcastBundle\Admin\Block;

use Martin1982\LiveBroadcastBundle\Service\GoogleRedirectService;
use Martin1982\LiveBroadcastBundle\Service\YouTubeApiService;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class YouTubeBlockService
 * @package Martin1982\LiveBroadcastBundle\Admin\Block
 */
class YouTubeBlockService extends BaseBlockService
{
    /**
     * @var YouTubeApiService
     */
    protected $youTubeApi;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * YouTubeBlockService constructor.
     * @param string $name
     * @param EngineInterface $templating
     * @param YouTubeApiService $youTubeApi
     * @param RequestStack $requestStack
     * @param GoogleRedirectService $redirectService
     * @throws \Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastOutputException
     */
    public function __construct(
        $name,
        EngineInterface $templating,
        YouTubeApiService $youTubeApi,
        RequestStack $requestStack,
        GoogleRedirectService $redirectService
    ) {
        $this->youTubeApi = $youTubeApi;
        $this->requestStack = $requestStack;

        $redirectUri = $redirectService->getOAuthRedirectUrl();
        $this->youTubeApi->initApiClients($redirectUri);

        parent::__construct($name, $templating);
    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null $response
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();

        if ($refreshToken = $session->get('youTubeRefreshToken')) {
            $this->youTubeApi->getAccessToken($refreshToken);
        }

        $isAuthenticated = $this->youTubeApi->isAuthenticated();
        $state = mt_rand();

        if (!$isAuthenticated) {
            $session->set('state', $state);
            $session->set('authreferer', $request->getRequestUri());
        }

        return $this->renderResponse('LiveBroadcastBundle:Block:youtube_auth.html.twig', array(
            'isAuthenticated' => $isAuthenticated,
            'authUrl' => $isAuthenticated ? '#' : $this->youTubeApi->getAuthenticationUrl($state),
            'youTubeChannelName' => $session->get('youTubeChannelName'),
            'youTubeRefreshToken' => $session->get('youTubeRefreshToken'),
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ), $response);
    }
}

<?php
/**
 * This file is part of the RatchetBundle project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace P2\Bundle\RatchetBundle\Security;

use P2\Bundle\RatchetBundle\WebSocket\Client\ClientInterface;
use P2\Bundle\RatchetBundle\WebSocket\Client\ClientProviderInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

/**
 * Class AuthenticationSuccessHandler
 * @package P2\Bundle\RatchetBundle\Security
 */
class AuthenticationSuccessHandler
{
    /**
     * @var ClientProviderInterface
     */
    protected $clientProvider;

    /**
     * @param ClientProviderInterface $clientProvider
     */
    public function __construct(ClientProviderInterface $clientProvider)
    {
        $this->clientProvider = $clientProvider;
    }

    /**
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        /** @var ClientInterface $user */
        if ($user instanceof ClientInterface) {
            $accessToken = hash('sha256', uniqid(microtime(true)));
            $user->setAccessToken($accessToken);

            $this->clientProvider->updateClient($user);
        }
    }
}

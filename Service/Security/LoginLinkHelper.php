<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Security;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use League\Uri\Uri;
use League\Uri\UriModifier;
use Symfony\Component\Security\Http\LoginLink\LoginLinkDetails;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

use function urlencode;

/**
 * Class LoginLinkHelper
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class LoginLinkHelper
{
    public function __construct(
        private readonly ?LoginLinkHandlerInterface $loginLinkHandler,
    ) {
    }

    public function createLoginLink(UserInterface $user, string $redirect = null): ?LoginLinkDetails
    {
        if (null === $this->loginLinkHandler) {
            return null;
        }

        if (!$user->isEnabled()) {
            return null;
        }

        $link = $this->loginLinkHandler->createLoginLink($user);

        if (!empty($redirect)) {
            $uri = Uri::createFromString($link->getUrl());
            $uri = (string)UriModifier::appendQuery($uri, 'redirect_after=' . urlencode($redirect));
            $link = new LoginLinkDetails($uri, $link->getExpiresAt());
        }

        return $link;
    }
}

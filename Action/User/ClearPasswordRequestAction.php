<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Action\User;

use Ekyna\Bundle\AdminBundle\Action\AdminActionInterface;
use Ekyna\Bundle\ResourceBundle\Action\AbstractAction;
use Ekyna\Bundle\ResourceBundle\Action\HelperTrait;
use Ekyna\Bundle\ResourceBundle\Action\ManagerTrait;
use Ekyna\Bundle\UiBundle\Action\FlashTrait;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Action\Permission;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ClearPasswordRequestAction
 * @package Ekyna\Bundle\UserBundle\Action\User
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ClearPasswordRequestAction extends AbstractAction implements AdminActionInterface
{
    use HelperTrait;
    use ManagerTrait;
    use FlashTrait;

    /**
     * @var int
     */
    private int $tokenTtl;

    /**
     * Constructor.
     *
     * @param int $tokenTtl
     */
    public function __construct(int $tokenTtl)
    {
        $this->tokenTtl = $tokenTtl;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(): Response
    {
        /** @var UserInterface $resource */
        $resource = $this->context->getResource();

        throw new RuntimeException('Not yet implemented');

        /*if ($resource->isPasswordRequestNonExpired($this->tokenTtl)) {
            $resource
                ->setConfirmationToken(null)
                ->setPasswordRequestedAt();

            $event = $this->getManager()->update($resource);
            $event->toFlashes($this->getFlashBag());
            $this->addFlash('ekyna_user.user.alert.password_request_cleared', 'success');
        } else {
            $this->addFlash('ekyna_user.user.alert.no_password_request', 'warning');
        }*/

        return $this->redirect($this->generateResourcePath($resource));
    }

    /**
     * @inheritDoc
     */
    public static function configureAction(): array
    {
        return [
            'permission' => Permission::UPDATE,
            'route'      => [
                'name'     => 'admin_%s_clear_password_request',
                'path'     => '/clear-password-request',
                'resource' => true,
                'methods'  => 'GET',
            ],
            'button'     => [
                'label'        => 'user.button.clear_password_request',
                'theme'        => 'warning',
                'trans_domain' => 'EkynaUser',
            ],
        ];
    }
}

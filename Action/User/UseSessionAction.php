<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Action\User;

use Ekyna\Bundle\AdminBundle\Action\AdminActionInterface;
use Ekyna\Bundle\ResourceBundle\Action\AbstractAction;
use Ekyna\Bundle\ResourceBundle\Action\SessionTrait;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Action\Permission;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class UseSessionAction
 * @package Ekyna\Bundle\UserBundle\Action\User
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UseSessionAction extends AbstractAction implements AdminActionInterface
{
    use SessionTrait;

    public function __invoke(): Response
    {
        /** @var UserInterface $user */
        $user = $this->context->getResource();

        //$this->isGranted('VIEW', $user); // TODO custom permission ('IMPERSONATE' ?)

        $token = new UsernamePasswordToken($user, null, 'front', $user->getRoles());

        $this->getSession()->set('_security_front', serialize($token));

        return $this->redirect('/');
    }

    /**
     * @inheritDoc
     */
    public static function configureAction(): array
    {
        return [
            'permission' => Permission::UPDATE,
            'route'      => [
                'name'     => 'admin_%s_use_session',
                'path'     => '/use-session',
                'resource' => true,
                'methods'  => 'GET',
            ],
            'button'     => [
                'label'        => 'user.button.use_session',
                'theme'        => 'primary',
                'icon'         => 'eye-open',
                'trans_domain' => 'EkynaUser',
            ],
        ];
    }
}

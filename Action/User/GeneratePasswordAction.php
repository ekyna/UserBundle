<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Action\User;

use Ekyna\Bundle\AdminBundle\Action\AdminActionInterface;
use Ekyna\Bundle\ResourceBundle\Action\AbstractAction;
use Ekyna\Bundle\ResourceBundle\Action\HelperTrait;
use Ekyna\Bundle\ResourceBundle\Action\ManagerTrait;
use Ekyna\Bundle\ResourceBundle\Action\ResourceEventDispatcherTrait;
use Ekyna\Bundle\UiBundle\Action\FlashTrait;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Action\Permission;
use Ekyna\Component\Resource\Event\ResourceMessage;
use Ekyna\Component\Resource\Exception\UnexpectedTypeException;
use Ekyna\Component\User\Service\Security\SecurityUtil;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GeneratePasswordAction
 * @package Ekyna\Bundle\UserBundle\Action
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class GeneratePasswordAction extends AbstractAction implements AdminActionInterface
{
    use HelperTrait;
    use ManagerTrait;
    use ResourceEventDispatcherTrait;
    use FlashTrait;

    private SecurityUtil $securityUtil;

    public function __construct(SecurityUtil $securityUtil)
    {
        $this->securityUtil = $securityUtil;
    }

    public function __invoke(): Response
    {
        /** @var UserInterface $resource */
        $resource = $this->context->getResource();

        if (!$resource instanceof UserInterface) {
            throw new UnexpectedTypeException($resource, UserInterface::class);
        }

        $redirect = $this->generateResourcePath($resource);

        $manager = $this->getManager();
        $event = $manager->createResourceEvent($resource);

        $dispatcher = $this->getResourceEventDispatcher();

        // Pre generate event
        $dispatcher->dispatch($event, UserEvents::PRE_GENERATE_PASSWORD);
        if ($event->isPropagationStopped()) {
            $this->addFlashFromEvent($event);

            return $this->redirect($redirect);
        }

        // New password
        $password = $this->securityUtil->generatePassword();

        $resource->setPlainPassword($password);

        $event
            ->addMessage(new ResourceMessage(
                sprintf('Generated password : "%s".', $password),
                ResourceMessage::TYPE_INFO
            ))
            ->addData('password', $password);

        // Update event
        $manager->update($event);

        if (!$event->isPropagationStopped()) {
            // Post Generate event
            $dispatcher->dispatch($event, UserEvents::POST_GENERATE_PASSWORD);
        }

        $this->addFlashFromEvent($event);

        return $this->redirect($redirect);
    }

    public static function configureAction(): array
    {
        return [
            'permission' => Permission::UPDATE,
            'route'      => [
                'name'     => 'admin_%s_generate_password',
                'path'     => '/generate-password',
                'resource' => true,
                'methods'  => 'GET',
            ],
            'button'     => [
                'label'        => 'user.button.generate_password',
                'theme'        => 'warning',
                'trans_domain' => 'EkynaUser',
            ],
        ];
    }
}

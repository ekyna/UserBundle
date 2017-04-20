<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Service\Mailer\UserMailer;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Event\ResourceMessage;
use Ekyna\Component\Resource\Exception\UnexpectedTypeException;
use Ekyna\Component\User\EventListener\UserEventSubscriber as BaseListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserEventSubscriber
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserEventSubscriber extends BaseListener implements EventSubscriberInterface
{
    protected UserMailer $mailer;

    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onPostCreate(ResourceEventInterface $event): void
    {
        $user = $this->getUserFromEvent($event);
        if (!$user->getSendCreationEmail()) {
            return;
        }

        $this->sendCredentials($event);
    }

    public function onPostGeneratePassword(ResourceEventInterface $event): void
    {
        $this->sendCredentials($event);
    }

    /**
     * Sends the user credentials by email.
     */
    private function sendCredentials(ResourceEventInterface $event): void
    {
        if (!$event->hasData('password')) {
            return;
        }

        if (empty($password = $event->getData('password'))) {
            return;
        }

        $user = $this->getUserFromEvent($event);

        $this->mailer->sendCreation($user, $password);

        $event->addMessage(
            ResourceMessage::create('user.message.credentials_sent')->setDomain('EkynaUser')
        );
    }

    protected function getUserFromEvent(ResourceEventInterface $event): UserInterface
    {
        $resource = $event->getResource();

        if (!$resource instanceof UserInterface) {
            throw new UnexpectedTypeException($resource, UserInterface::class);
        }

        return $resource;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::PRE_CREATE             => ['onPreCreate', 0],
            UserEvents::POST_CREATE            => ['onPostCreate', 0],
            UserEvents::INSERT                 => ['onInsert', 0],
            UserEvents::UPDATE                 => ['onUpdate', 0],
            UserEvents::POST_GENERATE_PASSWORD => ['onPostGeneratePassword', 0],
        ];
    }
}

<?php

namespace Ekyna\Bundle\UserBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class UserFormSubscriber
 * @package Ekyna\Bundle\UserBundle\Form\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserFormSubscriber implements EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $event->getData();

        if (0 == strlen($user->getUsername())) {
            $user->setUsername($user->getEmail());
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => ['onPostSubmit', 2048],
        ];
    }
}

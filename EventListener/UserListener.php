<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Event\ResourceMessage;
use Ekyna\Bundle\UserBundle\Event\UserEvents;
use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Ekyna\Bundle\UserBundle\Model\UserManagerInterface;
use Ekyna\Component\Resource\Exception\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserListener implements EventSubscriberInterface
{
    /**
     * @var EntityRepository
     */
    protected $groupRepository;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;


    /**
     * Constructor.
     *
     * @param EntityRepository              $groupRepository
     * @param UserManagerInterface          $fosUserManager
     * @param Mailer                        $mailer
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        EntityRepository $groupRepository,
        UserManagerInterface $fosUserManager,
        Mailer $mailer,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->groupRepository = $groupRepository;
        $this->userManager = $fosUserManager;
        $this->mailer = $mailer;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Pre create event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreCreate(ResourceEventInterface $event)
    {
        $user = $this->getUserFromEvent($event);

        // Generates a secured password.
        if (0 === strlen($user->getPlainPassword())) {
            $this->userManager->generatePassword($user);
            $user->setEnabled(true);

            // Warn about the generated password
            $password = $user->getPlainPassword();
            $event
                ->addMessage(new ResourceMessage(
                    sprintf('Generated password : "%s".', $password),
                    ResourceMessage::TYPE_INFO
                ))
                ->addData('password', $password);
        }

        $this->userManager->updateUsername($user);
        // TODO remove ? (done by the fos user listener)
        $this->userManager->updatePassword($user);
        $this->userManager->updateCanonicalFields($user);
    }

    /**
     * Post create event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPostCreate(ResourceEventInterface $event)
    {
        if (!$event->hasData('password')) {
            return;
        }

        $user = $this->getUserFromEvent($event);
        if (!$user->getSendCreationEmail()) {
            return;
        }

        if (0 < $this->mailer->sendCreationEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.message.credentials_sent'));
        }
    }

    /**
     * Pre update resource event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreUpdate(ResourceEventInterface $event)
    {
        $user = $this->getUserFromEvent($event);

        $this->userManager->updateUsername($user);
        // TODO remove ? (done by the fos user listener)
        $this->userManager->updatePassword($user);
        $this->userManager->updateCanonicalFields($user);
    }

    /**
     * Pre delete event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreDelete(ResourceEventInterface $event)
    {
        $this->getUserFromEvent($event);

        if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $event->addMessage(new ResourceMessage(
                'ekyna_user.user.message.delete_access_denied',
                ResourceMessage::TYPE_ERROR
            ));
        }
    }

    /**
     * Post generate password event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPostGeneratePassword(ResourceEventInterface $event)
    {
        if (!$event->hasData('password')) {
            return;
        }

        $user = $this->getUserFromEvent($event);

        if (0 < $this->mailer->sendNewPasswordEmailMessage($user, $event->getData('password'))) {
            $event->addMessage(new ResourceMessage('ekyna_user.user.message.credentials_sent'));
        }
    }

    /**
     * Returns the user form the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return UserInterface
     */
    protected function getUserFromEvent(ResourceEventInterface $event)
    {
        $resource = $event->getResource();

        if (!$resource instanceof UserInterface) {
            throw new InvalidArgumentException("Expected instance of " . UserInterface::class);
        }

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::PRE_CREATE             => ['onPreCreate', 0],
            UserEvents::POST_CREATE            => ['onPostCreate', 0],
            UserEvents::PRE_UPDATE             => ['onPreUpdate', 0],
            UserEvents::PRE_DELETE             => ['onPreDelete', 0],
            UserEvents::POST_GENERATE_PASSWORD => ['onPostGeneratePassword', 0],
        ];
    }
}

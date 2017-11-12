<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBundle\Event\GroupEvents;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Event\ResourceMessage;
use Ekyna\Component\Resource\Exception\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class GroupListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class GroupListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;


    /**
     * Constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Pre delete event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreDelete(ResourceEventInterface $event)
    {
        $this->getGroupFromEvent($event);

        if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $event->addMessage(new ResourceMessage(
                'ekyna_user.group.message.delete_access_denied',
                ResourceMessage::TYPE_ERROR
            ));
        }
    }

    /**
     * Returns the group form the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return GroupInterface
     */
    protected function getGroupFromEvent(ResourceEventInterface $event)
    {
        $resource = $event->getResource();

        if (!$resource instanceof GroupInterface) {
            throw new InvalidArgumentException("Expected instance of " . GroupInterface::class);
        }

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            GroupEvents::PRE_DELETE  => ['onPreDelete', 0],
        ];
    }
}

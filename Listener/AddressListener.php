<?php

namespace Ekyna\Bundle\UserBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Ekyna\Bundle\UserBundle\Model\AddressInterface;

/**
 * Class AddressListener
 * @package Ekyna\Bundle\UserBundle\Listener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressListener
{
    /**
     * Pre persist event handler.
     *
     * @param AddressInterface $address
     */
    public function prePersist(AddressInterface $address)
    {
        if (null !== $user = $address->getUser()) {
            if (0 == strlen($address->getGender())) {
                $address->setGender($user->getGender());
            }
            if (0 == strlen($address->getFirstName())) {
                $address->setFirstName($user->getFirstName());
            }
            if (0 == strlen($address->getLastName())) {
                $address->setLastName($user->getLastName());
            }
        }
    }

    /**
     * Pre update event handler.
     *
     * @param AddressInterface $address
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(AddressInterface $address, PreUpdateEventArgs $event)
    {
        if (null !== $user = $address->getUser()) {
            if (0 == strlen($address->getGender())) {
                $event->setNewValue('gender', $user->getGender());
            }
            if (0 == strlen($address->getFirstName())) {
                $event->setNewValue('firstName', $user->getFirstName());
            }
            if (0 == strlen($address->getLastName())) {
                $event->setNewValue('lastName', $user->getLastName());
            }
        }
    }
}

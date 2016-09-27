<?php

namespace Ekyna\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @package Ekyna\Bundle\UserBundle\DataFixtures\ORM
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $om)
    {
        $faker = Factory::create($this->container->getParameter('hautelook_alice.locale'));

        $userManager = $this->container->get('fos_user.user_manager');

        // Creates 3 surveys
        for ($s = 0; $s < 16; $s++) {
            $user = $userManager->createUser();
            $user->setEmail($faker->safeEmail);

            $userManager->generatePassword($user);
            $userManager->updateUser($user, true);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }
}

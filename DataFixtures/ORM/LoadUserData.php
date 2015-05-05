<?php

namespace Ekyna\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @package Ekyna\Bundle\UserBundle\DataFixtures\ORM
 * @author Étienne Dauvergne <contact@ekyna.com>
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
        $util = PhoneNumberUtil::getInstance();

        $userManager = $this->container->get('fos_user.user_manager');

        $genders = call_user_func($this->container->getParameter('ekyna_user.gender_class').'::getConstants');

        // Creates 3 surveys
        for ($s = 0; $s < 90; $s++) {
            $user = $userManager->createUser();
            $user
                ->setCompany($faker->company)
                ->setGender($faker->randomElement($genders))
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhone($util->parse($faker->phoneNumber, 'FR'))
                ->setMobile(50 < rand(0,100) ? $util->parse($faker->phoneNumber, 'FR') : null)
                ->setEmail($faker->safeEmail)
            ;

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

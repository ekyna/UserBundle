<?php

namespace Ekyna\Bundle\UserBundle\Install;

use Ekyna\Bundle\InstallBundle\Install\AbstractInstaller;
use Ekyna\Bundle\InstallBundle\Install\OrderedInstallerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class UserInstaller
 * @package Ekyna\Bundle\UserBundle\Install
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserInstaller extends AbstractInstaller implements OrderedInstallerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Default groups
     */
    protected $groups = [
        'Utilisateur' => ['ROLE_USER'],
    ];


    /**
     * {@inheritdoc}
     */
    public function install(Command $command, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>[User] Creating users groups:</info>');
        $this->createGroups($output);
        $output->writeln('');
    }

    /**
     * Creates the default groups.
     *
     * @param OutputInterface $output
     */
    private function createGroups(OutputInterface $output)
    {
        $em = $this->container->get('ekyna_user.group.manager');
        $repository = $this->container->get('ekyna_user.group.repository');

        foreach ($this->groups as $name => $roles) {
            $output->write(sprintf(
                '- <comment>%s</comment> %s ',
                $name,
                str_pad('.', 44 - mb_strlen($name), '.', STR_PAD_LEFT)
            ));

            /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $group */
            if (null !== $group = $repository->findOneBy(['name' => $name])) {
                $output->writeln('<comment>exists</comment>');

                continue;
            }

            /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $group */
            $group = $repository->createNew();
            $group
                ->setName($name)
                ->setRoles($roles);

            $em->persist($group);

            $output->writeln('<info>created</info>');
        }

        $em->flush();
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'ekyna_user';
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return -1024;
    }
}

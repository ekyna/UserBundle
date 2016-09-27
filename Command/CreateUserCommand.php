<?php

namespace Ekyna\Bundle\UserBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class CreateUserCommand
 * @package Ekyna\Bundle\UserBundle\Command
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ekyna:user:create')
            ->setDescription('Creates a user.')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email address.')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password.')
            ->setHelp(<<<EOT
The <info>ekyna:user:create</info> command creates a super admin user:

  <info>php app/console ekyna:user:create</info>

You can also optionally specify the user datas (email, password):

  <info>php app/console ekyna:user:create john.doe@example.org password</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $userInput = new UserInputInteract($this->getContainer()->get('ekyna_user.user.repository'));

        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelperSet()->get('question');

        $userInput->interact($input, $output, $helper);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManager = $this->getContainer()->get('fos_user.user_manager');
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $userManager->createUser();
        $user
            ->setPlainPassword($input->getArgument('password'))
            ->setEmail($input->getArgument('email'))
            ->setEnabled(true);

        $userManager->updateUser($user);

        $output->writeln('User has been successfully created.');
    }
}

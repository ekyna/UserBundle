<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Command;

use Ekyna\Bundle\UserBundle\Factory\UserFactoryInterface;
use Ekyna\Bundle\UserBundle\Manager\UserManagerInterface;
use Ekyna\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateUserCommand
 * @package Ekyna\Bundle\UserBundle\Command
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'ekyna:user:create-user';

    private UserRepositoryInterface $repository;
    private UserManagerInterface $manager;
    private UserFactoryInterface $factory;


    public function __construct(
        UserRepositoryInterface $repository,
        UserManagerInterface $manager,
        UserFactoryInterface $factory
    ) {
        parent::__construct();

        $this->repository = $repository;
        $this->manager = $manager;
        $this->factory = $factory;
    }

    protected function configure(): void
    {
        $this
            ->setName('ekyna:user:create')
            ->setDescription('Creates a user.')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email address.')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password.')
            ->setHelp(
                <<<EOT
The <info>ekyna:user:create</info> command creates a new user:

  <info>php app/console ekyna:user:create</info>

You can also optionally specify the user data (email, password):

  <info>php app/console ekyna:user:create john.doe@example.org password</info>
EOT
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $userInput = new UserInputInteract($this->repository);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelperSet()->get('question');

        $userInput->interact($input, $output, $helper);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->factory->create();

        $user
            ->setEmail($input->getArgument('email'))
            ->setPlainPassword($input->getArgument('password'))
            ->setEnabled(true);

        $event = $this->manager->save($user);

        if ($event->hasErrors()) {
            $output->writeln('<error>Failed to create user</error>');

            return 1;
        }

        $output->writeln('<info>User has been successfully created</info>');

        return 0;
    }
}

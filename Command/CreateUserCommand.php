<?php

namespace Ekyna\Bundle\UserBundle\Command;

use Ekyna\Bundle\UserBundle\Model\UserManagerInterface;
use Ekyna\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
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
    protected static $defaultName = 'ekyna:user:create';

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserManagerInterface
     */
    private $userManager;


    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param UserManagerInterface    $userManager
     */
    public function __construct(UserRepositoryInterface $userRepository, UserManagerInterface $userManager)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->userManager    = $userManager;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $userInput = new UserInputInteract($this->userRepository);

        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelperSet()->get('question');

        $userInput->interact($input, $output, $helper);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $this->userManager->createUser();
        $user
            ->setPlainPassword($input->getArgument('password'))
            ->setEmail($input->getArgument('email'))
            ->setEnabled(true);

        $this->userManager->updateUser($user);

        $output->writeln('User has been successfully created.');
    }
}

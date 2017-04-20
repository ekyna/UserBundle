<?php
declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Command;

use Ekyna\Bundle\UserBundle\Repository\UserRepositoryInterface;
use RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use function filter_var;

/**
 * Class UserInputInteract
 * @package Ekyna\Bundle\UserBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserInputInteract
{
    private UserRepositoryInterface $repository;


    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fills input with missing arguments by asking to user.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $helper
     */
    public function interact(InputInterface $input, OutputInterface $output, QuestionHelper $helper): void
    {
        $questions = [];

        $repository = $this->repository;

        if (!$input->getArgument('email')) {
            $question = new Question('Email: ');
            $question->setValidator(function ($answer) use ($repository) {
                if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                    throw new RuntimeException('This is not a valid email address.');
                }
                if (null !== $repository->findOneBy(['email' => $answer])) {
                    throw new RuntimeException('This email address is already used.');
                }

                return $answer;
            });
            $question->setMaxAttempts(3);

            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Password (hidden): ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $question->setValidator(function ($answer) {
                if (6 > strlen($answer)) {
                    throw new RuntimeException('Password should be composed of at least 6 characters.');
                }

                return $answer;
            });
            $question->setMaxAttempts(3);

            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $input->setArgument($name, $helper->ask($input, $output, $question));
        }
    }
}

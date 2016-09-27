<?php

namespace Ekyna\Bundle\UserBundle\Command;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class UserInputInteract
 * @package Ekyna\Bundle\UserBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserInputInteract
{
    /**
     * @var EntityRepository
     */
    private $userRepository;


    /**
     * Constructor.
     *
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Fills input with missing arguments by asking to user.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $helper
     */
    public function interact(InputInterface $input, OutputInterface $output, QuestionHelper $helper)
    {
        $questions = [];

        $repository = $this->userRepository;

        if (!$input->getArgument('email')) {
            $question = new Question('Email: ');
            $question->setValidator(function ($answer) use ($repository) {
                if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                    throw new \RuntimeException('This is not a valid email address.');
                }
                if (null !== $repository->findOneBy(['email' => $answer])) {
                    throw new \RuntimeException('This email address is already used.');
                }

                return $answer;
            });
            $question->setMaxAttempts(3);

            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Password: ');
            $question->setValidator(function ($answer) {
                if (!(preg_match('#^[a-zA-Z0-9]+$#', $answer) && strlen($answer) > 5)) {
                    throw new \RuntimeException('Password should be composed of at least 6 letters and numbers.');
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

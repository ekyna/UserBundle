<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Command;

use Ekyna\Bundle\UserBundle\Repository\TokenRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PurgeLoginTokenCommand
 * @package Ekyna\Bundle\UserBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class PurgeTokenCommand extends Command
{
    protected static $defaultName = 'ekyna:user:purge-token';

    private TokenRepository $repository;


    public function __construct(TokenRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->repository->getPurgeQuery()->execute();

        return Command::SUCCESS;
    }
}

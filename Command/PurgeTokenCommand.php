<?php

namespace Ekyna\Bundle\UserBundle\Command;

use Ekyna\Bundle\UserBundle\Repository\LoginTokenRepository;
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

    /**
     * @var LoginTokenRepository
     */
    private $repository;


    /**
     * Constructor.
     *
     * @param LoginTokenRepository $repository
     */
    public function __construct(LoginTokenRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->repository->getPurgeQuery()->execute();
    }
}

<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Repository;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Repository\ResourceRepositoryInterface;
use Ekyna\Component\User\Repository\UserRepositoryInterface as BaseRepository;

/**
 * Class UserRepositoryInterface
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @implements ResourceRepositoryInterface<UserInterface>
 */
interface UserRepositoryInterface extends BaseRepository
{

}

<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Repository;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\User\Repository\UserRepositoryInterface as BaseRepository;

/**
 * Class UserRepositoryInterface
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @method UserInterface|null find(int $id)
 * @method UserInterface|null findOneBy(array $criteria, array $sorting = [])
 * @method UserInterface[] findAll()
 * @method UserInterface[] findBy(array $criteria, array $sorting = [], int $limit = null, int $offset = null)
 */
interface UserRepositoryInterface extends BaseRepository
{

}

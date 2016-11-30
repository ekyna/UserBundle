<?php

namespace Ekyna\Bundle\UserBundle\Search;

use Ekyna\Component\Resource\Search\Elastica\ResourceRepository;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Search
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends ResourceRepository
{
    /**
     * @inheritdoc
     */
    protected function getDefaultMatchFields()
    {
        return [
            'email',
            'username',
        ];
    }
}

<?php

namespace Ekyna\Bundle\UserBundle\Search;

use Ekyna\Bundle\AdminBundle\Search\SearchRepositoryInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Repository;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Search
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends Repository implements SearchRepositoryInterface
{
    /**
     * Search users.
     *
     * @param string  $expression
     * @param integer $limit
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface[]
     */
    public function defaultSearch($expression, $limit = 10)
    {
        if (0 == strlen($expression)) {
            $query = new Query\MatchAll();
        } else {
            $query = new Query\MultiMatch();
            $query
                ->setQuery($expression)
                ->setFields(array('email', 'first_name', 'last_name'));
        }

        return $this->find($query, $limit);
    }
}

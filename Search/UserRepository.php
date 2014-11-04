<?php

namespace Ekyna\Bundle\UserBundle\Search;

use Ekyna\Bundle\AdminBundle\Search\SearchRepositoryInterface;
use Elastica\Query\QueryString;
use FOS\ElasticaBundle\Repository;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Search
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends Repository implements SearchRepositoryInterface
{
    public function defaultSearch($text)
    {
        $query = new QueryString();
        $query
            ->setDefaultOperator('OR')
            ->setParam('query', $text)
            ->setParam('fields', array('email', 'firstName', 'lastName'))
        ;

        return $this->find($query);
    }
}

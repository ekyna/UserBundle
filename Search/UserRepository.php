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
            ->setParam('query', $text)
            ->setParam('fields', array('email', '*name'))
        ;

        return $this->find($query);
    }
}

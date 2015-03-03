<?php

namespace Ekyna\Bundle\UserBundle\Search;

use Ekyna\Bundle\AdminBundle\Search\SearchRepositoryInterface;
use Elastica\Query;
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
        if (0 == strlen($text)) {
            $query = new Query\MatchAll();
        } else {
            $query = new Query\MultiMatch();
            $query
                ->setQuery($text)
                ->setFields(array('email', 'first_name', 'last_name'))
            ;
        }

        return $this->find($query);
    }
}

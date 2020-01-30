<?php

namespace Ekyna\Bundle\UserBundle\Service\Search;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Repository\GroupRepository;
use Ekyna\Component\Resource\Search\Elastica\ResourceRepository;
use Ekyna\Component\Resource\Search\Request;
use Ekyna\Component\Resource\Search\Result;
use Elastica\Query;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Search
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends ResourceRepository
{
    /**
     * @var GroupRepository
     */
    private $groupRepository;


    /**
     * Sets the group repository.
     *
     * @param GroupRepository $repository
     */
    public function setGroupRepository(GroupRepository $repository): void
    {
        $this->groupRepository = $repository;
    }

    /**
     * @inheritDoc
     */
    protected function createQuery(Request $request): Query\AbstractQuery
    {
        $query = parent::createQuery($request);

        if (empty($roles = (array)$request->getParameter('roles', []))) {
            return $query;
        }

        // TODO Use scalar result to ids directly
        if (empty($groups = $this->groupRepository->findByRoles($roles))) {
            return $query;
        }

        $groupsIds = [];
        foreach ($groups as $group) {
            $groupsIds[] = $group->getId();
        }

        $bool = new Query\BoolQuery();
        $bool
            ->addMust($query)
            ->addMust(new Query\Terms('group', $groupsIds));

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function createResult($source, Request $request): ?Result
    {
        if (null === $result = parent::createResult($source, $request)) {
            return null;
        }

        $id = $source instanceof UserInterface ? $source->getId() : $source['id'];

        return $result
            ->setIcon('fa fa-user')
            ->setRoute('ekyna_user_user_admin_show')
            ->setParameters(['userId' => $id]);
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultFields(): array
    {
        return [
            'first_name',
            'first_name.analyzed',
            'last_name',
            'last_name.analyzed',
            'email',
            'email.analyzed',
        ];
    }
}

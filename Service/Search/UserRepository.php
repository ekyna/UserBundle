<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Search;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Bridge\Symfony\Elastica\SearchRepository;
use Ekyna\Component\Resource\Search\Request;
use Ekyna\Component\Resource\Search\Result;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Search
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends SearchRepository
{
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
            ->setRoute('admin_ekyna_user_user_read') // TODO Use resource/action
            ->setParameters(['userId' => $id]);
    }

    protected function getDefaultFields(): array
    {
        return [
            'email',
            'email.analyzed',
        ];
    }
}

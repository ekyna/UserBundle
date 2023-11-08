<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Serializer;

use Ekyna\Bundle\UserBundle\Model;
use Ekyna\Component\Resource\Bridge\Symfony\Serializer\ResourceNormalizer;

/**
 * Class UserNormalizer
 * @package Ekyna\Bundle\UserBundle\Service\Serializer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserNormalizer extends ResourceNormalizer
{
    /**
     * @inheritDoc
     *
     * @param Model\UserInterface $object
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if ($format === 'csv' && self::contextHasGroup('TableExport', $context)) {
            return (string)$object;
        }

        $data = parent::normalize($object, $format, $context);

        if (self::contextHasGroup(['Default', 'User', 'Search'], $context)) {
            $data = array_replace([
                'email' => $object->getEmail(),
            ], $data);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        //$resource = parent::denormalize($data, $class, $format, $context);

        throw new \Exception('Not yet implemented');
    }
}

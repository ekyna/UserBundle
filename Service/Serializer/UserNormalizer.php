<?php

namespace Ekyna\Bundle\UserBundle\Service\Serializer;

use Ekyna\Bundle\UserBundle\Model;
use Ekyna\Component\Resource\Serializer\AbstractResourceNormalizer;

/**
 * Class UserNormalizer
 * @package Ekyna\Bundle\UserBundle\Service\Serializer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserNormalizer extends AbstractResourceNormalizer
{
    /**
     * @inheritdoc
     *
     * @param Model\UserInterface $user
     */
    public function normalize($user, $format = null, array $context = [])
    {
        $groups = isset($context['groups']) ? (array)$context['groups'] : [];

        if ($format === 'csv' && in_array('TableExport', $groups)) {
            return (string)$user;
        }

        $data = parent::normalize($user, $format, $context);

        /** @var Model\UserInterface $user */

        if (in_array('Default', $groups) || in_array('Search', $groups)) {
            $data = array_replace([
                'username' => $user->getUsername(),
                'email'    => $user->getEmail(),
                'group'    => $user->getGroup()->getId(),
            ], $data);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        //$resource = parent::denormalize($data, $class, $format, $context);

        throw new \Exception('Not yet implemented');
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Model\UserInterface;
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return class_exists($type) && is_subclass_of($type, Model\UserInterface::class);
    }
}

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
        if ($format === 'csv' && $this->contextHasGroup('TableExport', $context)) {
            return (string)$user;
        }

        $data = parent::normalize($user, $format, $context);

        if ($this->contextHasGroup(['Default', 'User', 'Search'], $context)) {
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

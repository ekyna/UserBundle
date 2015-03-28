<?php

namespace Ekyna\Bundle\UserBundle\Model;

use Ekyna\Bundle\CoreBundle\Model\AbstractConstants;

/**
 * Class Genders
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Genders extends AbstractConstants
{
    const MR = 'mr';
    const MRS = 'mrs';
    const MISS = 'miss';

    /**
     * {@inheritdoc}
     */
    public static function getConfig()
    {
        $short = 'ekyna_user.gender.short.';
        $long  = 'ekyna_user.gender.long.';

        return array(
            self::MR   => array($short.self::MR,   $long.self::MR),
            self::MRS  => array($short.self::MRS,  $long.self::MRS),
            self::MISS => array($short.self::MISS, $long.self::MISS),
        );
    }

    /**
     * Returns the constant choices.
     *
     * @param bool $long
     * @return array
     */
    public static function getChoices($long = false)
    {
        $offset = $long ? 1 : 0;
        $choices = [];
        foreach (static::getConfig() as $constant => $config) {
            $choices[$constant] = $config[$offset];
        }
        return $choices;
    }

    /**
     * Returns the label for the given constant.
     *
     * @param mixed $constant
     * @param bool  $long
     * @return string
     */
    public static function getLabel($constant, $long = false)
    {
        static::isValid($constant, true);

        return static::getConfig()[$constant][$long ? 1 : 0];
    }
}

<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Model;

use DateTime;
use Ekyna\Bundle\UserBundle\Entity\Token;
use InvalidArgumentException;

/**
 * Trait TokenTrait
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
trait TokenTrait
{
    private array $config;


    /**
     * Configures the token expiration.
     *
     * @param array $config
     */
    public function configureExpiration(array $config): void
    {
        $this->config = array_replace([
            Token::TYPE_REGISTRATION => '1 hour',
            Token::TYPE_RESETTING    => '15 mins',
        ], $config);
    }

    /**
     * Returns the expiration date for the given token type.
     *
     * @param string $type
     *
     * @return DateTime
     */
    protected function getExpiresAt(string $type): DateTime
    {
        if (!isset($this->config[$type])) {
            throw new InvalidArgumentException("Unexpected token type '$type'.");
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return new DateTime('+' . $this->config[$type]);
    }
}

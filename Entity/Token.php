<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Entity;

use DateTime;
use DateTimeInterface;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use InvalidArgumentException;

use function in_array;
use function sprintf;

/**
 * Class Token
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Token
{
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_RESETTING    = 'resetting';

    private static array $types = [
        self::TYPE_REGISTRATION,
        self::TYPE_RESETTING,
    ];

    private string             $type;
    private ?int               $id        = null;
    private ?UserInterface     $user      = null;
    private ?string            $hash      = null;
    private array              $data      = [];
    private DateTimeInterface  $createdAt;
    private ?DateTimeInterface $expiresAt = null;


    /**
     * Validates the token type.
     */
    public static function validateType(string $type): void
    {
        if (!in_array($type, self::$types, true)) {
            throw new InvalidArgumentException(sprintf("Unexpected token type '%s'.", $type));
        }
    }

    public function __construct(string $type)
    {
        self::validateType($type);

        $this->type = $type;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): Token
    {
        $this->user = $user;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): Token
    {
        $this->hash = $hash;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): Token
    {
        $this->data = $data;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $date): Token
    {
        $this->createdAt = $date;

        return $this;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeInterface $date): Token
    {
        $this->expiresAt = $date;

        return $this;
    }
}

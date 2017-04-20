<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Twig;

use Exception;
use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class UserExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserExtension extends AbstractExtension
{
    protected array $accountConfig;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->accountConfig = $config;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ekyna_user_account_var',
                [$this, 'getAccountVar']
            ),
        ];
    }

    /**
     * Returns the account var.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAccountVar(string $key)
    {
        $config = $this->accountConfig;
        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!isset($config[$key])) {
                throw new InvalidArgumentException("Key '$key' is not defined.");
            }

            $config = $config[$key];
        }

        return $config;
    }
}

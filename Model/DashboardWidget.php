<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Model;

use Ekyna\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatableInterface;

use function is_null;
use function Symfony\Component\Translation\t;

/**
 * Class DashboardWidget
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @property array<DashboardWidgetButton> $buttons
 */
class DashboardWidget
{
    private string                 $template;
    private ?TranslatableInterface $title      = null;
    private string                 $theme      = 'default';
    private array                  $parameters = [];
    private int                    $priority   = 0;
    private array                  $buttons    = [];
    private bool                   $panel      = false;

    /**
     * @param TranslatableInterface|string|null $title
     */
    public static function create(string $template, $title = null): DashboardWidget
    {
        if (is_string($title)) {
            $title = t($title);
        } elseif (!is_null($title) && !$title instanceof TranslatableInterface) {
            throw new UnexpectedTypeException($title, [TranslatableInterface::class, 'string', 'null']);
        }

        return new self($template, $title);
    }

    public function __construct(string $template, ?TranslatableInterface $title)
    {
        $this->setTemplate($template);
        $this->setTitle($title);
    }

    public function getTitle(): ?TranslatableInterface
    {
        return $this->title;
    }

    public function setTitle(?TranslatableInterface $title): DashboardWidget
    {
        $this->title = $title;

        $this->panel = !!$title;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): DashboardWidget
    {
        $this->template = $template;

        return $this;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): DashboardWidget
    {
        $this->theme = $theme;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): DashboardWidget
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): DashboardWidget
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return array<DashboardWidgetButton>
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    public function addButton(DashboardWidgetButton $button): DashboardWidget
    {
        $this->buttons[] = $button;

        return $this;
    }

    public function isPanel(): bool
    {
        return $this->panel;
    }

    public function setPanel(bool $panel): DashboardWidget
    {
        $this->panel = $panel;

        return $this;
    }
}

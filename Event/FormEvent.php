<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Event;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class FormEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class FormEvent extends Event
{
    public const FORM_REGISTRATION = 'ekyna_user.form.registration';
    public const FORM_PROFILE      = 'ekyna_user.form.profile';

    private FormBuilderInterface $builder;

    public function __construct(FormBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function getBuilder(): FormBuilderInterface
    {
        return $this->builder;
    }
}

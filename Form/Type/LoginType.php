<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LoginType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LoginType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var RequestStack
     */
    private $requestStack;


    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param RequestStack          $requestStack
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', Type\TextType::class, [
                'label' => 'ekyna_core.field.email_address',
            ])
            ->add('_password', Type\PasswordType::class, [
                'label' => 'ekyna_core.field.password',
            ])
            ->add('_target_path', Type\HiddenType::class);

        if ($options['remember_me']) {
            $builder->add('_remember_me', Type\CheckboxType::class, [
                'label'    => 'ekyna_core.field.remember_me',
                'required' => false,
                'attr'     => [
                    'align_with_widget' => true,
                ],
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $data = $event->getData();

            $data['_target_path'] = $this->getTargetPath($options);

            $event->setData($data);
        });
    }

    /**
     * Returns the target path.
     *
     * @param array $options
     *
     * @return string
     */
    protected function getTargetPath(array $options)
    {
        // By options
        if (isset($options['target_path']) && (null !== $targetPath = $options['target_path'])) {
            return $targetPath;
        }

        // By request parameter
        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            return $request->query->get('target_path');
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'target_path' => null,
                'remember_me' => true,
                'method'      => 'POST',
                'action'      => function (Options $options) {
                    $parameters = [];
                    if (null !== $targetPath = $this->getTargetPath((array) $options)) {
                        $parameters['target_path'] = $targetPath;
                    }

                    return $this->urlGenerator->generate('fos_user_security_check', $parameters);
                },
            ])
            ->setAllowedTypes('target_path', ['null', 'string'])
            ->setAllowedTypes('remember_me', 'bool');
    }
}

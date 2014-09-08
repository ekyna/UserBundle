<?php

namespace Ekyna\Bundle\UserBundle\Listener;

use Ekyna\Bundle\SettingBundle\Manager\SettingsManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AuthenticationListener
 * @package Ekyna\Bundle\UserBundle\Listener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var SettingsManagerInterface
     */
    private $settingsManager;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param TranslatorInterface $translator
     * @param SettingsManagerInterface $settingsManager
     */
    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        TranslatorInterface $translator,
        SettingsManagerInterface $settingsManager
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->settingsManager = $settingsManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            //AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }

    /**
     * Handle login failure event.
     *
     * @param AuthenticationFailureEvent $event
     */
    /*public function onAuthenticationFailure(AuthenticationFailureEvent $event) {}*/

    /**
     * Handle login success event.
     *
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        /** @var \Ekyna\Bundle\UserBundle\Entity\User $user */
        $user = $event->getAuthenticationToken()->getUser();

        if (! $user->getGroup()->hasRole('ROLE_ADMIN')) {
            return;
        }

        $siteName  = $this->settingsManager->getParameter('general.site_name');
        $adminMail = $this->settingsManager->getParameter('general.admin_email');
        $adminName = $this->settingsManager->getParameter('general.admin_name');

        $subject = $this->translator->trans(
            'ekyna_user.email.login_success.subject',
            array('%sitename%' => $siteName)
        );

        $userName = sprintf('%s %s', $user->getFirstname(), $user->getLastname());

        $body = $this->twig->render(
            'EkynaUserBundle:Security:login_success_email.html.twig',
            array(
                'username' => $userName,
                'sitename' => $siteName,
                'date' => new \DateTime()
            )
        );

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($adminMail, $adminName)
            ->setTo($user->getEmail(), $userName)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}

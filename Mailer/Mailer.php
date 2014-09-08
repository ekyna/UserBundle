<?php

namespace Ekyna\Bundle\UserBundle\Mailer;

use Ekyna\Bundle\SettingBundle\Manager\SettingsManager;
use FOS\UserBundle\Mailer\Mailer as BaseMailer;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Mailer
 * @package Ekyna\Bundle\UserBundle\Mailer
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Mailer extends BaseMailer
{
    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Sets the settings manager.
     *
     * @param SettingsManager $settingsManager
     */
    public function setSettingsManager(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        /** @var \Ekyna\Bundle\UserBundle\Entity\User $user */
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $sitename = $this->settingsManager->getParameter('general.site_name');
        $username = sprintf('%s %s', $user->getFirstname(), $user->getLastname());

        $rendered = $this->templating->render($template, array(
            'username' => $username,
            'confirmationUrl' => $url,
            'sitename' => $sitename,
        ));

        $subject = $this->translator->trans(
            'ekyna_user.email.registration.subject',
            array('%sitename%' => $sitename)
        );

        $this->sendEmail($rendered, $user->getEmail(), $username, $subject);
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        /** @var \Ekyna\Bundle\UserBundle\Entity\User $user */
        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $sitename = $this->settingsManager->getParameter('general.site_name');
        $username = sprintf('%s %s', $user->getFirstname(), $user->getLastname());

        $rendered = $this->templating->render($template, array(
            'username' => $username,
            'confirmationUrl' => $url,
            'sitename' => $sitename,
        ));

        $subject = $this->translator->trans(
            'ekyna_user.email.resetting.subject',
            array('%sitename%' => $sitename)
        );

        $this->sendEmail($rendered, $user->getEmail(), $username, $subject);
    }

    /**
     * @param string $renderedTemplate
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     */
    protected function sendEmail($renderedTemplate, $toEmail, $toName, $subject)
    {
        $fromEmail = $this->settingsManager->getParameter('general.admin_email');
        $fromName = $this->settingsManager->getParameter('general.admin_name');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail, $fromName)
            ->setTo($toEmail, $toName)
            ->setBody($renderedTemplate, 'text/html');

        $this->mailer->send($message);
    }
}

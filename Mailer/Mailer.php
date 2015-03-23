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
    /** @var \Swift_Mailer $mailer */
    protected $mailer;

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

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Sends an email to the user to warn about successful login.
     *
     * @param UserInterface $user
     */
    public function sendSuccessfulLoginEmailMessage(UserInterface $user)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $siteName  = $this->settingsManager->getParameter('general.site_name');
        $userName = sprintf('%s %s', $user->getFirstName(), $user->getLastName());

        $rendered = $this->templating->render(
            'EkynaUserBundle:Security:login_success_email.html.twig',
            array(
                'username' => $userName,
                'sitename' => $siteName,
                'date' => new \DateTime()
            )
        );

        $subject = $this->translator->trans(
            'ekyna_user.email.login_success.subject',
            array('%sitename%' => $siteName)
        );

        $this->sendEmail($rendered, $user->getEmail(), $userName, $subject);
    }

    /**
     * Sends an email to the user to warn about account creation.
     *
     * @param UserInterface $user
     * @param string        $password
     * @return integer
     */
    public function sendCreationEmailMessage(UserInterface $user, $password)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $siteName  = $this->settingsManager->getParameter('general.site_name');
        $userName = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
        $login = $user->getUsername();

        if (0 === strlen($password)) {
            return 0;
        }

        $rendered = $this->templating->render(
            'EkynaUserBundle:Admin/User:creation_email.html.twig',
            array(
                'username' => $userName,
                'sitename' => $siteName,
                'login'    => $login,
                'password' => $password,
            )
        );

        $subject = $this->translator->trans(
            'ekyna_user.email.creation.subject',
            array('%sitename%' => $siteName)
        );

        return $this->sendEmail($rendered, $user->getEmail(), $userName, $subject);
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $sitename = $this->settingsManager->getParameter('general.site_name');
        $username = sprintf('%s %s', $user->getFirstName(), $user->getLastName());

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
        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $sitename = $this->settingsManager->getParameter('general.site_name');
        $username = sprintf('%s %s', $user->getFirstName(), $user->getLastName());

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
     * Sends the message.
     *
     * @param string $renderedTemplate
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     *
     * @return integer
     */
    protected function sendEmail($renderedTemplate, $toEmail, $toName, $subject)
    {
        $fromEmail = $this->settingsManager->getParameter('notification.from_email');
        $fromName = $this->settingsManager->getParameter('notification.from_name');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail, $fromName)
            ->setTo($toEmail, $toName)
            ->setBody($renderedTemplate, 'text/html');

        return $this->mailer->send($message);
    }
}

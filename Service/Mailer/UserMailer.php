<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Mailer;

use Ekyna\Bundle\SettingBundle\Manager\SettingManagerInterface;
use Ekyna\Bundle\UserBundle\Entity\Token;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use LogicException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class UserMailer
 * @package Ekyna\Bundle\UserBundle\Service\Mailer
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserMailer
{
    protected SettingManagerInterface $setting;
    protected TranslatorInterface     $translator;
    protected Environment             $twig;
    protected UrlGeneratorInterface   $urlGenerator;
    protected MailerInterface         $mailer;
    protected array                   $config;

    public function __construct(
        SettingManagerInterface $settingsManager,
        TranslatorInterface $translator,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        MailerInterface $mailer,
        array $config
    ) {
        $this->setting = $settingsManager;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * Sends an email to the user to warn about account creation.
     */
    public function sendCreation(UserInterface $user, string $password): void
    {
        $siteName = $this->setting->getParameter('general.site_name');
        $login = $user->getUsername();

        if (empty($password)) {
            throw new LogicException('Password is empty');
        }

        if (null === $loginUrl = $this->getLoginUrl()) {
            return;
        }

        $body = $this->twig->render('@EkynaUser/Email/creation_email.html.twig', [
            'sitename'  => $siteName,
            'login_url' => $loginUrl,
            'login'     => $login,
            'password'  => $password,
        ]);

        $subject = $this->translator->trans('email.creation.subject', [
            '%sitename%' => $siteName,
        ], 'EkynaUser');

        $this->sendEmail($user->getEmail(), $subject, $body);
    }

    /**
     * Sends an email to the user to warn about the new password.
     */
    public function sendNewPassword(UserInterface $user, string $password): void
    {
        $siteName = $this->setting->getParameter('general.site_name');
        $login = $user->getUsername();

        if (empty($password)) {
            throw new LogicException('Password is empty');
        }

        if (null === $loginUrl = $this->getLoginUrl()) {
            return;
        }

        $body = $this->twig->render('@EkynaUser/Email/new_password_email.html.twig', [
            'sitename'  => $siteName,
            'login_url' => $loginUrl,
            'login'     => $login,
            'password'  => $password,
        ]);

        $subject = $this->translator->trans('email.new_password.subject', [
            '%sitename%' => $siteName,
        ], 'EkynaUser');

        $this->sendEmail($user->getEmail(), $subject, $body);
    }

    /**
     * Sends registration check email.
     */
    public function sendRegistrationCheck(Token $token): void
    {
        $template = $this->config['registration']['email'];

        $url = $this->urlGenerator->generate(
            'ekyna_user_account_registration_register',
            ['token' => $token->getHash()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $siteName = $this->setting->getParameter('general.site_name');

        $body = $this->twig->render($template, [
            'confirmationUrl' => $url,
            'sitename'        => $siteName,
        ]);

        $subject = $this->translator->trans('email.registration.subject', [
            '%sitename%' => $siteName,
        ], 'EkynaUser');

        $this->sendEmail($token->getData()['email'], $subject, $body);
    }

    /**
     * Sends password resetting check email.
     */
    public function sendResettingCheck(Token $token): void
    {
        if (!$user = $token->getUser()) {
            throw new LogicException('Token has no user.');
        }

        $template = $this->config['resetting']['email'];

        $url = $this->urlGenerator->generate(
            'ekyna_user_account_resetting_reset',
            ['token' => $token->getHash()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $siteName = $this->setting->getParameter('general.site_name');

        $body = $this->twig->render($template, [
            'reset_url' => $url,
            'sitename'  => $siteName,
        ]);

        $subject = $this->translator->trans('email.resetting.subject', [
            '%sitename%' => $siteName,
        ], 'EkynaUser');

        $this->sendEmail($user->getEmail(), $subject, $body);
    }

    /**
     * Returns the login url.
     */
    protected function getLoginUrl(): ?string
    {
        if ($this->config['enabled']) {
            return $this->urlGenerator->generate('ekyna_user_security_login', [], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return null;
    }

    /**
     * Sends the message.
     */
    protected function sendEmail(string $recipient, string $subject, string $body): void
    {
        $fromEmail = $this->setting->getParameter('notification.from_email');
        $fromName = $this->setting->getParameter('notification.from_name');

        $sender = new Address($fromEmail, $fromName);

        $message = new Email();
        $message
            ->from($sender)
            ->replyTo($sender)
            ->to($recipient)
            ->subject($subject)
            ->html($body);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->mailer->send($message);
    }
}

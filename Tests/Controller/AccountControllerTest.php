<?php

namespace Ekyna\Bundle\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AccountControllerTest
 * @package Ekyna\Bundle\UserBundle\Tests\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountControllerTest extends WebTestCase
{
    public function testSecuredAccount()
    {
        $client = static::createClient();

        // Go to account home page
        $crawler = $client->request('GET', '/account/');

        // Response is a redirection to login page ?
        $this->assertRegExp('/\/login$/', $client->getResponse()->headers->get('location'));

        // Follow redirection
        $crawler = $client->followRedirect();

        // Check that this is login page
        $this->assertCount(1, $crawler->filter('html:contains("Connectez-vous à votre compte client")'));
        $this->assertCount(1, $crawler->filter('html:contains("Créez votre compte client")'));
    }

    public function testRegistration()
    {
        $client = static::createClient();

        // Go to registration page
        $crawler = $client->request('GET', '/account/register/');

        // Check that this is registration page
        $this->assertCount(1, $crawler->filter('html:contains("Créez votre compte client")'));

        $form = $crawler->selectButton('submit')->form();

        // Fill the form
        $form['fos_user_registration_form[email]']                 = 'john.doe@example.com';
        $form['fos_user_registration_form[username]']              = 'johndoe35';
        $form['fos_user_registration_form[plainPassword][first]']  = 'password';
        $form['fos_user_registration_form[plainPassword][second]'] = 'password';

        $form['fos_user_registration_form[company]']   = 'Test company';
        $form['fos_user_registration_form[gender]']->select('mr');
        $form['fos_user_registration_form[firstname]'] = 'John';
        $form['fos_user_registration_form[lastname]']  = 'Doe';
        $form['fos_user_registration_form[phone]']     = '0212345678';
        $form['fos_user_registration_form[mobile]']    = '0687654321';

        $crawler = $client->submit($form);

        // Response is a redirection to confirmed page ?
        $this->assertRegExp('/\/register\/confirmed$/', $client->getResponse()->headers->get('location'));

        // Follow redirection
        $crawler = $client->followRedirect();

        // Check that registration succeed
        $this->assertCount(1, $crawler->filter('html:contains("Compte activé")'));
        $this->assertCount(1, $crawler->filter('html:contains("Félicitations johndoe35, votre compte est maintenant activé.")'));
    }

    public function testLogin()
    {
        $client = static::createClient();

        // Go to login page
        $crawler = $client->request('GET', '/account/login');

        // Check that this is login page
        $this->assertCount(1, $crawler->filter('html:contains("Connectez-vous à votre compte client")'));

        // Fill the form with wrong credentials
        $form = $crawler->selectButton('Connexion')->form();
        $form['_username']   = 'wronglogin';
        $form['_password']   = 'wrongpassword';
        $crawler = $client->submit($form);

        // Response is a redirection to login page ?
        $this->assertRegExp('/\/account\/login$/', $client->getResponse()->headers->get('location'));
        $crawler = $client->followRedirect();

        // Login failed ?
        $this->assertCount(1, $crawler->filter('html:contains("Nom d\'utilisateur ou mot de passe incorrect")'));

        // Fill the form with previously created user's credentials
        $form = $crawler->selectButton('Connexion')->form();
        $form['_username']   = 'johndoe35';
        $form['_password']   = 'password';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        // Home Page
        $this->assertCount(1, $crawler->filter('html:contains("Accueil")'));
    }
}

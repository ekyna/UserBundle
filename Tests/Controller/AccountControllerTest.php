<?php

namespace Ekyna\Bundle\UserBundle\Tests\Controller;

use Ekyna\Bundle\UserBundle\Tests\WebTestCase;

/**
 * Class AccountControllerTest
 * @package Ekyna\Bundle\UserBundle\Tests\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountControllerTest extends WebTestCase
{
    public function testSecuredAccount()
    {
        // Go to account home page
        $this->client->request('GET', '/account/');

        // Response is a redirection to login page ?
        $this->assertRegExp('#/account/login$#', $this->client->getResponse()->headers->get('location'));

        // Follow redirection
        $crawler = $this->client->followRedirect();

        // Check that this is login page
        $this->assertCount(1, $crawler->filter('html:contains("Connectez-vous à votre compte client")'));
        $this->assertCount(1, $crawler->filter('html:contains("Créez votre compte client")'));
    }

    public function testRegistration()
    {
        // Go to registration page
        $crawler = $this->client->request('GET', '/account/register/');

        // Check that this is registration page
        $this->assertCount(1, $crawler->filter('html:contains("Créez votre compte client")'));

        $form = $crawler->selectButton('submit')->form();

        // Fill the form
        $form['fos_user_registration_form[email]']                 = 'john.doe@example.com';
//        $form['fos_user_registration_form[username]']              = 'johndoe';
        $form['fos_user_registration_form[plainPassword][first]']  = 'password';
        $form['fos_user_registration_form[plainPassword][second]'] = 'password';

        $form['fos_user_registration_form[company]']   = 'Test company';
        $form['fos_user_registration_form[gender]']->select('mr');
        $form['fos_user_registration_form[firstName]'] = 'John';
        $form['fos_user_registration_form[lastName]']  = 'Doe';
        $form['fos_user_registration_form[phone]']     = '0212345678';
        $form['fos_user_registration_form[mobile]']    = '0687654321';

        $this->client->submit($form);

        // Response is a redirection to confirmed page ?
        $this->assertRegExp('#/register/confirmed/?#', $this->client->getResponse()->headers->get('location'));

        // Follow redirection
        $crawler = $this->client->followRedirect();

        // Check that registration succeed
        $this->assertCount(1, $crawler->filter('html:contains("Compte activé")'));
        $this->assertCount(1, $crawler->filter('html:contains("Félicitations ! Votre compte est maintenant activé.")'));
    }

    public function testLogin()
    {
        $this->logIn();

        $this->client->request('GET', '/account/register/');
        $this->assertTrue($this->client->getResponse()->isSuccessful(), 'Failed to reach account profile page.');
    }
}

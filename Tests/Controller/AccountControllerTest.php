<?php

namespace Ekyna\Bundle\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * AccountControllerTest
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountControllerTest extends WebTestCase
{
    public function testSecuredAccount()
    {
        $client = static::createClient();

        // Goes to account home page
        $crawler = $client->request('GET', '/account/');

        // Response is a redirection to login page ?
        $this->assertRegExp('/\/login$/', $client->getResponse()->headers->get('location'));

        // Follow redirection
        $crawler = $client->followRedirect();

        // Check that this is login page
        $this->assertCount(1, $crawler->filter('html:contains("Connectez-vous à votre compte client")'));
    }

    public function testRegistration()
    {
        $client = static::createClient();

        // Goes to registration page
        $crawler = $client->request('GET', '/account/register/');

        // Check that this is registration page
        $this->assertCount(1, $crawler->filter('html:contains("Créez votre compte client")'));

        $form = $crawler->selectButton('submit')->form();

        $form['fos_user_registration_form[email]']                 = 'john.doe@example.com';
        $form['fos_user_registration_form[username]']              = 'johndoe35';
        $form['fos_user_registration_form[plainPassword][first]']  = 'password';
        $form['fos_user_registration_form[plainPassword][second]'] = 'password';
        
        $form['fos_user_registration_form[company]']   = 'Test company';
        $form['fos_user_registration_form_gender_0']->tick();
        $form['fos_user_registration_form[firstname]'] = 'John';
        $form['fos_user_registration_form[lastname]']  = 'Doe';
        $form['fos_user_registration_form[phone]']     = '0212345678';
        $form['fos_user_registration_form[mobile]']    = '0687654321';

        $crawler = $client->submit($form);
        
    }
}

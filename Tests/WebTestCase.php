<?php

namespace Ekyna\Bundle\UserBundle\Tests;

use Ekyna\Bundle\CoreBundle\Tests\WebTestCase as BaseTestCase;

/**
 * Class WebTestCase
 * @package Ekyna\Bundle\UserBundle\Tests
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class WebTestCase extends BaseTestCase
{
    protected function logIn()
    {
        $crawler = $this->client->request('GET', $this->generatePath('fos_user_security_login'));

        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => 'user@example.org',
            '_password'  => 'user',
        ));

        $this->client->submit($form);
        $this->client->followRedirect();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 30/04/2018
 * Time: 16:02
 */

namespace App\Tests\Controller;

/**
 * Class SecurityControllerTest
 * @package App\Tests\Controller
 */
class SecurityControllerTest extends AbstractControllerTest
{
    const USER_MAIL = 'jepedupont@test.com';
    const USER_PWD = 'abcdefghA1';

    public function testRegisterForm()
    {
        $this->createSchema();

        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        # Test with correct data
        $form = $crawler->selectButton("S'inscrire gratuitement")->form();
        $form['registration[firstname]'] = 'Jeanne';
        $form['registration[lastname]'] = 'Dupont';
        $form['registration[mail]'] = self::USER_MAIL;
        $form['registration[password][first]'] = self::USER_PWD;
        $form['registration[password][second]'] = self::USER_PWD;
        $form['registration[termsAccepted]'] = true;

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());

        $client->setMaxRedirects(1);
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Jeanne Dupont")')->count());

    }

    public function testLoginForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton("Se connecter")->form();
        $form['login[mail]'] = self::USER_MAIL;
        $form['login[password]'] = self::USER_PWD;

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        $client->setMaxRedirects(1);
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Jeanne Dupont")')->count());
    }

    /**
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        # 2 Assertions
        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->dropSchema();
    }

    /**
     * @return \Generator
     */
    public function urlProvider()
    {
        yield ['/register'];
        yield ['/login'];
    }
}
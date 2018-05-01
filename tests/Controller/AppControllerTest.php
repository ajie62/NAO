<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 01/05/2018
 * Time: 11:17
 */

namespace App\Tests\Controller;

/**
 * Class AppControllerTest
 * Total assertion number: 8
 * @package App\Tests\Controller
 */
class AppControllerTest extends AbstractControllerTest
{
    /**
     * Tests on homepage
     * Assertion number: 3
     */
    public function testHome()
    {
        $this->createSchema();

        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink("Explorer")->link();
        $crawler = $client->click($link);

        # Passes if the client reaches the search page
        $this->assertEquals(1, $crawler->filter(".map-container")->count());

        # Go back to homepage
        $crawler = $client->back();

        $form = $crawler->selectButton("S'inscrire gratuitement")->form();
        $form['registration[firstname]'] = ' ';
        $form['registration[lastname]'] = 'aaa';
        $form['registration[mail]'] = 'abcdef@aaa';
        $form['registration[password][first]'] = 'abcd';
        $form['registration[password][second]'] = 'efgh';
        $form['registration[termsAccepted]'] = true;

        $crawler = $client->submit($form);

        # Passes if the html contains error for password (amongst other errors)
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Les mots de passe doivent être identiques.")')->count()
        );

        $link = $crawler->selectLink("REJOIGNEZ-NOUS")->link();
        $crawler = $client->click($link);

        # Passes if the client reaches the registration page after clicking on "REJOIGNEZ-NOUS"
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Inscrivez-vous gratuitement")')->count()
        );
    }

    /**
     * Tests on contact page
     * Assertion number: 1
     */
    public function testContact()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $form = $crawler->selectButton("Envoyer")->form();
        $form['contact[mail]'] = 'aaa@test.com';
        $form['contact[subject]'] = 'Test';
        $form['contact[message]'] = 'I am testing the contact form';

        $crawler = $client->submit($form);

        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Le reCAPTCHA n\'a pas été correctement entré. Veuillez réessayer.")')->count()
        );
    }

    /**
     * Assert that all pages are reached
     * Assertion number: 4
     *
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->dropSchema();
    }

    /**
     * @return \Generator
     */
    public function urlProvider()
    {
        yield ['/'];
        yield ['/contact'];
        yield ['/terms-of-use'];
        yield ['/about'];
    }
}

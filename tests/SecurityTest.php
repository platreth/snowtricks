<?php 
// tests/Util/CalculatorTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityTest extends WebTestCase
{
	public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
    	$client = self::createClient();
    	$crawler = $client->request('GET', '/register');

    	$this->assertGreaterThan(0,$crawler->filter('a:contains("Accueil")')->count());
    	$this->assertGreaterThan(0,$crawler->filter('a:contains("Connexion")')->count());
    	$this->assertGreaterThan(0,$crawler->filter('a:contains("Inscription")')->count());
    }

    public function testConnexion()
    {
    	$client = static::createClient();
    	$crawler = $client->request('GET', '/login');

    	$form = $crawler->selectButton('Ok')->form();

		// set some values
		$form['_username'] = 'hugo.platret@gmail.com';
		$form['_password'] = 'coucou';

		// submit the form
		$crawler = $client->submit($form);

		$this->assertTrue($client->getResponse()->isRedirect());
		$crawler = $client->followRedirect();
//		 var_dump($client->getResponse()->getContent());
//		 die();

        $this->assertEquals(0,
            $crawler->filter('li:contains("This value is not valid.")')->count()
        );    }
}
<?php 
// tests/Util/CalculatorTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityTest extends WebTestCase
{
	public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
    	$client = self::createClient();
    	$crawler = $client->request('GET', '/');		

    	$this->assertGreaterThan(0,$crawler->filter('a:contains("Accueil")')->count());
    	$this->assertGreaterThan(0,$crawler->filter('a:contains("Connexion")')->count());
    	$this->assertGreaterThan(0,$crawler->filter('a:contains("Inscription")')->count());
    }
//    public function testConnexion()
//    {
//    	$client = self::createClient();
//    	$crawler = $client->request('GET', '/');
//    	$link = $crawler
//    		->filter('a:contains("Connexion")') // find all links with the text "Greet"
//    		->eq(0) // select the second link in the list
//    		->link();
//
//    	$crawler = $client->click($link);
//
//    	$form = $crawler->selectButton('ok')->form();
//
//		// set some values
//		$form['_username'] = 'hugo.a@gmail.com';
//		$form['_password'] = 'coucou';
//
//		// submit the form
//		$crawler = $client->submit($form);
//
//		$this->assertTrue($client->getResponse()->isRedirect());
//		$crawler = $client->followRedirect();
//		// var_dump($client->getResponse()->getContent());
//		// die();
//
//		$this->assertGreaterThan(0,$crawler->filter('a:contains("Espace membre")')->count());
//
//
//
//    }
}
<?php
// tests/Util/TrickTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class TrickTest extends WebTestCase
{

    public function testCreate() {

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hugo.platret@gmail.com',
            'PHP_AUTH_PW'   => 'coucou',
        ]);


       $crawler = $client->request('GET', '/member/trick/new');

        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        $link_image = $crawler
            ->filter('a:contains("Ajouter une image")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link()
        ;
        $link_video = $crawler
            ->filter('a:contains("Ajouter une vidéo")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link()
        ;

        $form = $buttonCrawlerNode->form([
            'trick[name]'    => 'test',
            'trick[category]' => 2,
            'trick[description]' => 'Symfony rocks!',
         ]);

        $image = new UploadedFile(\dirname(__DIR__, 1) .  '/public/uploads/8cc936542ac311913368f77322434644.jpeg', 'photo.jpg', 'image/jpeg', null);

        $form['trick[cover]']->upload($image);


        $client->submit($form);


        //A FAIRE VERIF


    }

}

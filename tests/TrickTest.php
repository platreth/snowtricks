<?php
// tests/Util/TrickTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


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

// and click it
        $client->click($link_image);
        $client->click($link_image);
        $client->click($link_image);
        $client->click($link_video);

        $form = $buttonCrawlerNode->form([
            'trick[name]'    => 'test',
            'trick[category]' => 2,
            'trick[description]' => 'Symfony rocks!',
         ]);
        //new uploaded file
        $form['trick[images][O]']->upload('/uploads/1f729329d57d85d8cb2ed2d07236b95a.jpeg');
        $form['trick[images][1]']->upload('/uploads/2a9e3a1e7206dbf9cbe2fa37ae3bc07c.jpeg');
        $form['trick[images][2]']->upload('/uploads/8cc936542ac311913368f77322434644.jpeg');
        $form['trick[videos][O]']->upload('/uploads/video/file_example_mp4_480_1_5mg-5dc32a0d456ab.mp4');

        $client->submit($form);

    }
}

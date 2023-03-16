<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//Classe qui teste toutes les urls du projet
class DefaultTest extends WebTestCase
{  /**
    * @dataProvider getUrlsPages
    */
    public function testerToutesLesPages($url): void
    {
        $client = static::createClient();
        //la page que l'on veut tester
        $crawler = $client->request('GET', $url);
        $this->assertResponseIsSuccessful();

    }

    public function getUrlsPages():\Generator {
            yield "page d'accueil" => ['/'];
            yield "page about us" => ['/aboutus'];
            yield "page des wishes" => ['/wish/'];
            //yield "page un wish" => ['/wish/detail/'];
    }
}

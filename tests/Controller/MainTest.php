<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testPageHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        /*$this->assertSelectorTextContains('h1', 'Hello World');*/
        $this->assertCount(1,$crawler->filter('nav'));
        $this->assertSelectorTextContains('body',('Lorem'));
    }
}

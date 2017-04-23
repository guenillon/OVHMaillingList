<?php

namespace MaillingListBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin');
    }

    public function testOvh()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ovh');
    }

    public function testMaillinglist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/maillingList');
    }

}

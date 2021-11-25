<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostControllerTest
 * @package App\Tests\Controller
 */
class PostControllerTest extends WebTestCase
{
    public function testGetPosts(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        static::assertResponseIsSuccessful();
    }
}
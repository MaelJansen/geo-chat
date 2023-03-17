<?php

namespace App\Tests;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Persistence\ManagerRegistry;

class MessageApiTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testFindClose(): void
    {
        $repo = $this->createMock(ManagerRegistry::class);
        $messageRepo = new MessageRepository($repo);
        $message = $messageRepo->findClose(-0,57389, 44,8451032, 100);
        $this->assertEquals(json_decode($message, true)[1], "Super la #foire place des quinconces!");
        $this->assertResponseIsSuccessful();
    }
}

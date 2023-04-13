<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlsControllerTest extends WebTestCase
{
    public function testCreate(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertPageTitleSame('Botly');
        $this->assertSelectorTextContains('h1', 'The best Url shortener out there!');
        $this->assertSelectorExists('input[id="form_original"]');
        $this->assertSelectorExists('input[placeholder="Enter the URL to shorten here !"]');
    }
}

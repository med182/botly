<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlsControllerTest extends WebTestCase
{
    public function testHomepage_should_display_url_shortener_form(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertPageTitleSame('Botly');
        $this->assertSelectorTextContains('h1', 'The best Url shortener out there!');
        $this->assertSelectorExists('input[id="form_original"]');
        $this->assertSelectorExists('input[placeholder="Enter the URL to shorten here !"]');
    }

    public function testForm_should_work_with_valid_data()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $form = $crawler->filter('form')->form();
        $client->submit($form, ['form[original]' => 'https://python.org']);

        $this->assertResponseRedirects();
    }
}

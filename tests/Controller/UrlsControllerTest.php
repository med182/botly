<?php

namespace App\Tests\Controller;

use App\Utils\Str;
use App\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlsControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertPageTitleSame('Botly');
        $this->assertSelectorTextContains('h1', 'The best Url shortener out there!');
        $this->assertSelectorExists('input[id="form_original"]');
        $this->assertSelectorExists('input[placeholder="Enter the URL to shorten here !"]');
    }

    public function testCreateForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $form = $crawler->filter('form')->form();
        $client->submit($form, ['form[original]' => 'https://python.org']);

        $this->assertResponseRedirects();
    }


    public function testShow()
    {
        $client = static::createClient();


        $em =  $this->getContainer()->get('doctrine.orm.entity_manager');

        $original = 'https://airbnb.com';
        $url = new Url;
        $url->setOriginal($original);
        $shortened = Str::random(6);
        $url->setShortened($shortened);

        $em->persist($url);
        $em->flush();

        $client->request('GET', '/' . $shortened);
        $this->assertResponseRedirects($original);
    }

    public function testPreview()
    {
        $client = static::createClient();


        $em =  $this->getContainer()->get('doctrine.orm.entity_manager');

        $original = 'https://parlonscode.com';
        $url = new Url;
        $url->setOriginal($original);
        $shortened = Str::random(6);
        $url->setShortened($shortened);

        $em->persist($url);
        $em->flush();

        $crawler = $client->request('GET', sprintf("/%/preview" . $shortened));
        $this->assertSelectorTextContains('h1', 'Yay ! Here is your shortened URL:');
        $this->assertSelectorTextContains('a', 'http://localhost/' . $shortened);
        $this->assertSelectorTextContains('body', 'Go back home' . $shortened);
        $this->assertSame('Go back home', $crawler->filter('a')->eq(1)->text());
        dd($crawler->filter(' a')->eq(0)->text());

        $client->clickLink('Go back home');
        $this->assertRouteSame('app_home');
    }
}

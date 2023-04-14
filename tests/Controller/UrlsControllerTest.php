<?php

namespace App\Tests\Controller;

use App\Entity\Url;
use Illuminate\Support\Str;
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

    // public function testPreview()
    // {
    //     $client = static::createClient();


    //     $em =  $this->getContainer()->get('doctrine.orm.entity_manager');

    //     $original = 'https://twiter.com';
    //     $shortened = Str::random(6);
    //     $url = new Url;
    //     $url->setOriginal($original);

    //     $url->setShortened($shortened);

    //     $em->persist($url);
    //     $em->flush();

    //     $crawler = $client->request('GET', sprintf('/%s/preview', $shortened));
    //     $this->assertSelectorTextContains('h1', 'Yay ! Here is your shortened URL:');
    //     $this->assertSelectorTextContains('h1 >a', 'http://localhost/' . $shortened);




    //     $client->clickLink('Go back home');
    //     $this->assertRouteSame('app_home');
    // }
    // public function testShow_return_404()
    // {

    //     $client = static::createClient();
    //     $client->request('GET', '/qwerty');

    //     $this->assertResponseStatusCodeSame(404);
    // }
    public function testPreview_return_404()
    {

        $client = static::createClient();
        $client->request('GET', '/poiuyt/preview');

        $this->assertResponseStatusCodeSame(404);
    }
}

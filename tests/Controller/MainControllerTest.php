<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testIndexPageLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form'); // vérifie qu’un formulaire est présent
        $this->assertSelectorTextContains('button', 'Envoyer');
    }

    public function testFormSubmissionCreatesContact(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Envoyer')->form([
            'form[firstName]' => 'Alice',
            'form[name]' => 'Dupont',
            'form[message]' => 'Bonjour !',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/'); // après envoi, redirection
        $client->followRedirect();

        // Vérifie que la page s’est bien rechargée
        $this->assertSelectorExists('form');
    }

    public function testListPageLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $client->request('GET', '/liste/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table'); // si tu affiches les contacts dans un tableau
    }
}

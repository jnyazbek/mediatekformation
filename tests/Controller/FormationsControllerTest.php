<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

/**
 * Description of FormationsControllerTest
 *
 * @author joseph-nicolasyazbek
 */
class FormationsControllerTest {
    
    private const FORMATIONS = '/formations';

    // Test d'accès à la page
    public function testIndexFormation() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONS);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
     // Tri ASC et DESC sur les formations
    public function testTriFormations() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONS.'/tri/title/ASC');
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
        $client->request('GET', self::FORMATIONS.'/tri/title/DESC');
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
        
    }
    // Tri ASC et DESC sur les playlists

    public function testTriPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONS.'/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('h5', "Bases de la programmation n°74 - POO : collections");
        $client->request('GET',self::FORMATIONS.'/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5', "C# : ListBox en couleur");
    }

    // Tri ASC et DESC sur les dates
    public function testTriDates()
    {
        $client = static::createClient();
        $client->request('GET',self::FORMATIONS.'/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('h5', "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
        $client->request('GET',self::FORMATIONS.'/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('h5', "Eclipse n°2 : rétroconception avec ObjectAid");
    }

    // Filtre par titre de formation
    public function testFiltreFormations()
    {
        $client = static::createClient();
        $client->request('GET',self::FORMATIONS);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours UML'
        ]);
        $this->assertSelectorTextContains('h5', 'Cours UML (25 à 33 / 33) : exercices');
        $this->assertCount(6, $crawler->filter('h5'));
    }

    // Filtre par playlist
    public function testFiltrePlaylists()
    {
        $client = static::createClient();
        $client->request('GET',self::FORMATIONS);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'MCD'
        ]);
        $this->assertSelectorTextContains('h5', 'MCD exercice 18 : sujet 2006 (cas Credauto)');
        $this->assertCount(31, $crawler->filter('h5'));
    }

    // clic sur un lien
    public function testShowOne()
    {
        $client = static::createClient();
        $client->request('GET',self::FORMATIONS);
        $client->clickLink("image");
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals(self::FORMATIONS.'/formation/1', $uri);
        $this->assertSelectorTextContains('h4', 'Eclipse n°8 : Déploiement');
       
        /*$client = static::createClient();
        $crawler = $client->request('GET', '/formations/formation/1');

        $this->assertResponseIsSuccessful();*/
        

    }
}

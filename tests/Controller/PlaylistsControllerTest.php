<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of PlaylistsControllerTest
 *
 * @author joseph-nicolasyazbek
 */
namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase
{
    private const PLAYLISTS = '/playlists';

    // Test d'accès à la page
    public function testIndexPlaylists() {
        $client = static::createClient();
        $client->request('GET', self::PLAYLISTS);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    // Tri ASC et DESC sur les playlists

    public function testTriPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', self::PLAYLISTS.'/tri/name/ASC');
        $this->assertSelectorTextContains('h5', "Bases de la programmation (C#)");
        $client->request('GET', self::PLAYLISTS.'/tri/name/DESC');
        $this->assertSelectorTextContains('h5', "Visual Studio 2019 et C#");
    }

    // Filtre par playlist
    public function testFiltrePlaylists()
    {
        $client = static::createClient();
        $client->request('GET',self::PLAYLISTS);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Java'
        ]);
        $this->assertSelectorTextContains('h5', 'Eclipse et Java');
        $this->assertCount(2, $crawler->filter('h5'));
    }

    // Filtre par nombre de vidéos
    public function testFiltreNbVideo()
    {
        $client = static::createClient();
        $client->request('GET', self::PLAYLISTS.'/tri/countFormations/ASC');
        $this->assertSelectorTextContains('h5', "Cours Informatique embarquée");
        $client->request('GET', self::PLAYLISTS.'/tri/countFormations/DESC');
        $this->assertSelectorTextContains('h5', "Bases de la programmation (C#)");
    }

    // clic sur le bouton voir détail
    public function testDetail()
    {
        $client = static::createClient();
        $client->request('GET',self::PLAYLISTS);
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals(self::PLAYLISTS.'/playlist/13', $uri);
        $this->assertSelectorTextContains('h4', "Bases de la programmation (C#)");
    }
}
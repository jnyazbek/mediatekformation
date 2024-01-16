<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

/**
 * Description of PlaylistRepositorytest
 *
 * @author joseph-nicolasyazbek
 */
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    // Récupération du Repository Playlist

    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    // Tets du nombre de Playlist

    public function testNbPlaylists() {
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(28, $nbPlaylists);
    }

    // Définition du nom et titre de la playlist afin de tester l'ajout

    public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
                   ->setName("Titre de la playlist")
                   ->setDescription("Description de la playlist");
        return $playlist;
    }

    // Test ajout playlist

    public function testAddPlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylist = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylist + 1, $repository->count([]), "Erreur lors de l'ajout de la playlist");
    }

    // Test supression Playlist

    public function testRemovePlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylist = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylist - 1, $repository->count([]), "Erreur lors de la suppression de la playlist");
    }
    
    public function testFindAllOrderByName() {
        $repository = $this->recupRepository();
        $playlist = $repository->findAllOrderByName("DESC");
        $nbplaylist = count($playlist);
        $this->assertEquals(26, $nbplaylist);
        $this->assertEquals('Visual Studio 2019 et C#', $playlist[0]->getName());
        
    }
    
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $result = $this->repository->findByContainValue('name', 'Visual Studio 2019 et C#', 'playlist');
        $this->assertEquals('Visual Studio 2019 et C#',  $results[0]->getName());
    }

    public function testFindAllOrderedByFormationCount() {
        $results = $this->repository->findAllOrderedByFormationCount('ASC');
        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(2, count($results));

        $previousCount = 0;
        foreach ($results as $result) {
            $currentCount = count($result->getFormations());
            $this->assertGreaterThanOrEqual($previousCount, $currentCount);
            $previousCount = $currentCount;
        }
    }
}

<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author joseph-nicolasyazbek
 */
// tests/Repository/FormationRepositoryTest.php


class FormationRepositoryTest extends KernelTestCase
{
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }


    public function testNbFormations() {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(245, $nbFormations);
    }

    public function newFormation(): Formation {
        $formation = (new Formation())
                   ->setTitle("Titre de la formation")
                   ->setDescription("Description de la formation")
                   ->setPublishedAt(new DateTime('now'));
        return $formation;
    }

    public function testAddFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout de la formation");
        $repository->remove($formation,true);
    }

    public function testRemoveFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "Erreur lors de la suppression de la catégorie");
    }

    public function testFindAllOrderBy() {
        $repository = $this->recupRepository();
        $formations = $repository->findAllOrderBy("title", "DESC");
        $nbFormations = count($formations);
        $this->assertEquals(245, $nbFormations);
        $this->assertEquals("UML : Diagramme de paquetages", $formations[0]->getTitle());
        
    }
    
    

     
}


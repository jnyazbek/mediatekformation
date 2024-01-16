<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author joseph-nicolasyazbek
 */


class CategorieRepositoryTest extends KernelTestCase
{
    public function recupRepository(): CategorieRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }


    public function testNbCategories() {
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }

    public function newCategorie(): Categorie {
        $categorie = (new Categorie())
                   ->setName("Nom catégorie");
        return $categorie;
    }

    public function testAddCategorie() {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "Erreur lors de l'ajout de la catégorie");
        $repository->remove($categorie,true);
    }

    public function testRemoveCategorie() {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "Erreur lors de la suppression de la catégorie");
        
    }

    public function testFindOneByName() {
        $repository = $this->recupRepository();
        $categorie = $repository->findOneByName('SQL');
        $this->assertEquals('SQL', $categorie->getName(), "Erreur lors de la récupération par nom de la catégorie");
    }
}

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Validations;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
/**
 * Description of FormationValidationsTest
 *
 * @author joseph-nicolasyazbek
 */


class FormationValidationsTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::$container->get(ValidatorInterface::class);
    }

    public function testFormationDateNotInFuture()
    {
        $formation = new Formation();
        $formation->setTitle('Test Formation');
        $formation->setDescription('Test Description');
        $formation->setPublishedAt(new \DateTime('+1 day')); // Mets la date dans le futur

        $errors = $this->validator->validate($formation);
        $this->assertGreaterThan(0, count($errors), 'impossible de poster une formation dans le futur');

        // recherche un message d'erreur eventuel
        $hasDateError = false;
        foreach ($errors as $error) {
            if (str_contains($error->getPropertyPath(), 'publishedAt') && str_contains($error->getMessage(), 'ne peut pas être publiée dans le futur')) {
                $hasDateError = true;
                break;
            }
        }
        $this->assertTrue($hasDateError, 'Pas d erreur dans publishedAt.');
    }
}

<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtString()
    {
        $formation = new Formation();

        // Cas 1
        $date = new DateTime('2023-04-01 17:00:04');
        $formation->setPublishedAt($date);
        $this->assertEquals('01/04/2023', $formation->getPublishedAtString());

        // Case 2: Date is null
        $formation->setPublishedAt(null);
        $this->assertEquals('', $formation->getPublishedAtString());
    }
}


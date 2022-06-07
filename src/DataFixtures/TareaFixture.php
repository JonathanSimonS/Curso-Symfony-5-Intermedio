<?php

namespace App\DataFixtures;

use App\Entity\Tarea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TareaFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        // generar listado de tareas
        for ($i=1; $i = 24; $i++) { 
            $tarea=new Tarea();
            $tarea->setDescripcion("Tarea de prueba - $i");
            $manager->persist($tarea);    
        }

        $manager->flush();
    }
}

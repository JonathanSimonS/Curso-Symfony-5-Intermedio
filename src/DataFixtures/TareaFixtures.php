<?php

namespace App\DataFixtures;

use App\Entity\Tarea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

// el orden de la creación de datos se altera con implements DependentFixtureInterface
class TareaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        
        // generar listado de tareas para admin
        for ($i=0; $i < 10; $i++) { 
            $tarea=new Tarea();
            $tarea->setDescripcion("Tarea de prueba admin - $i");
            $tarea->setFinalizada(0);
            $tarea->setUsuario($this->getReference(UserFixtures::USUARIO_ADMIN_REFERENCIA));
            
            $manager->persist($tarea);    
        }

        // generar listado de tareas para user
        for ($i=0; $i < 9; $i++) { 
            $tarea=new Tarea();
            $tarea->setDescripcion("Tarea de prueba user - $i");
            $tarea->setFinalizada(0);
            $tarea->setUsuario($this->getReference(UserFixtures::USUARIO_USER_REFERENCIA));
            
            $manager->persist($tarea);    
        }

        $manager->flush();
    }

    // le indicamos el rden de prioridad 
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture 
{

    // generamos referencias para entidades
    public const USUARIO_ADMIN_REFERENCIA = 'user-admin';
    public const USUARIO_USER_REFERENCIA = 'user-user';

    // siempre que queremos usar un servicio lo cargamos a través del constructor, y en los controladores a través de métodos
    private $userPasswordEncoder;

    public function __construct(UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $usuario = new User();
        $usuario->setEmail('admin@admin.es');
        $usuario->setRoles(['ROLE_ADMIN']);
        $usuario->setPassword(
            $this->userPasswordEncoder->hashPassword(
                $usuario, 'admin'
                )
        );
        $manager->persist($usuario);
        $manager->flush();
        $this->addReference(self::USUARIO_ADMIN_REFERENCIA, $usuario);

        $usuario = new User();
        $usuario->setEmail('user@user.es');
        $usuario->setRoles(['ROLE_USER']);
        $usuario->setPassword(
            $this->userPasswordEncoder->hashPassword(
                $usuario, 'user'
                )
        );
        $manager->persist($usuario);
        $manager->flush();
        $this->addReference(self::USUARIO_USER_REFERENCIA, $usuario);

    }
}

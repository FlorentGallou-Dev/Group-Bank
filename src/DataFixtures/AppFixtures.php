<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use App\Entity\User;
use App\Entity\Account;
use App\Entity\Operation;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Boucle qui crée mes utilisateurs
        for ($i=1; $i < 5; $i++) { 
            $user = new User();
            $user->setEmail("useremail" . $i . "@exemple.com");
            $password = $this->encoder->encodePassword($user, "password" . $i);
            $user->setPassword($password);
            $user->setFirstname("Firstname" . $i);
            $user->setLastname("Lastname" . $i);
            $user->setSex("Male");
            $user->setBirthdate(new \DateTime("04/07/1989"));
            $user->setCity("City" . $i);
            $user->setCityCode("7600" . $i);
            // Génère un nombre aléatoire de comptes pour l'utilisateur
            for ($j=1; $j < 3; $j++) { 
                $account = new Account();
                $account->setAmount(mt_rand(1, 500));
                $account->setOpeningDate(new \DateTime());
                $account->setAccountType("PEL" . $j);
                $account->setUser($user);
                // Génère des opérations pour chaque compte
                for ($k=1; $k < 4; $k++) { 
                    $operation = new Operation();
                    $operation->setOperationType("debit");
                    $operation->setOperationAmount(mt_rand(1, 100));
                    $operation->setRegistered(new \DateTime());
                    $operation->setLabel("Random label" . $k);
                    $operation->setAccount($account);
                    $manager->persist($operation);
                }
                $manager->persist($account);
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}

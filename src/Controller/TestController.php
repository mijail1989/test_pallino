<?php

namespace App\Controller;

use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {   
        $this->entityManager = $entityManager;
    }

    #[Route('/shops', name: 'shops')]
    public function shops()
    {
        $entity = $this->entityManager->getRepository(Shop::class);
        return $this->json($entity->findAll());
    }
}
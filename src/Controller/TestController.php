<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Entity\Shop;
use App\Entity\Product;
use App\Service\ApiConnectorService;
use App\Service\ProductImporterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    private $apiConnector;
    private $entityManager;
    private $logger;
    private $productImporter;

    public function __construct(EntityManagerInterface $entityManager ,ApiConnectorService $apiConnector,LoggerInterface $logger, ProductImporterService $productImporter)
    {   
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->apiConnector = $apiConnector;
        $this->productImporter = $productImporter;
    }

    #[Route('/shops', name: 'shops')]
    public function shops()
    {
        $entity = $this->entityManager->getRepository(Discount::class);
        dd($entity->findAll()[0]);
        return $this->json($entity->findAll());
    }
}
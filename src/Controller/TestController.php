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

    CONST ENDPOINT = "https://test.pallinolabs.it/api/v1/offers.json";

    CONST DISCOUNT_ARRAY_MAPPING = [
        "id" => "setClientId",
        "shop_id" => "setShop",
        "product" => "setProduct",
        "price" => "setPrice",
        "currency" => "setCurrency",
        "description" => "setDescription"
    ];
    
    #[Route('/shops', name: 'shops')]
    public function shops()
    {
        $entity = $this->entityManager->getRepository(Discount::class);
        return $this->json($entity->findAll());
    }

    #[Route('/api/v1/offers/{shopID}', name: 'shopsID')]
    public function shopstest(Request $request)
    {
        $shopID = $request->attributes->get('shopID');
        $entity = $this->entityManager->createQueryBuilder()
        ->select('d')
        ->from(Discount::class, 'd')
        ->join('d.shop', 's')
        ->where('s.client_id = :shopID')
        ->setParameter('shopID', $shopID)
        ->orderBy('d.price', 'ASC')
        ->getQuery();

    $discounts = $entity->getResult();
    // Converte i dati in un array per la risposta JSON
    $responseData = [];
    foreach ($discounts as $discount) {
        $responseData[] = [
            'discount_id' => $discount->getId(),
            'shop_name' => $discount->getShop()->getName(),
            'product_name' => $discount->getProduct()->getName(),
            'price' => $discount->getPrice(),
            'currency' => $discount->getCurrency(),
            'description' => $discount->getDescription(),
        ];
    }

    // Restituisci la risposta JSON
    return $this->json($responseData);
    }

    // #[Route('/api/v1/offers/{countryCode}', name: 'shopsID')]
    // public function shopstesting(Request $request)
    // {
    //     $countryCode = $request->attributes->get('countryCode');
        
    //     $query = $this->entityManager->createQueryBuilder()
    //     ->select('d, s')
    //     ->from(Discount::class, 'd')
    //     ->join('d.shop', 's')
    //     ->where('s.country = :countryCode')
    //     ->setParameter('countryCode', $countryCode)
    //     ->getQuery();

    // $offers = $query->getResult();
    // // Converte i dati in un array per la risposta JSON
    // $responseData = [];
    // foreach ($offers as $offer) {
    //     $responseData[] = [
    //         'offer_id' => $offer->getId(),
    //         'shop_name' => $offer->getShop()->getName(),
    //         'shop_address' => $offer->getShop()->getAddress(),
    //         'product_name' => $offer->getProduct()->getName(),
    //         'price' => $offer->getPrice(),
    //         'currency' => $offer->getCurrency(),
    //         'description' => $offer->getDescription(),
    //     ];
    // }

    // // Restituisci la risposta JSON
    // return $this->json($responseData);
        
    // }



    private function importShopData(Array $discountData,Array $arrayMapping){
            $discount = $this->getOrCreateEntity(Discount::class, "client_id", $discountData['id']);

            foreach($arrayMapping as $key => $setMethodvalue){
                if(isset($discountData[$key])){
                    switch ($key) {
                        case "product":
                            $product = $this->productImporter->importData($discountData[$key]);
                            $discount->setProduct($product);
                            break;
                        case "shop_id":
                            // Se c'è solo un elemento
                            $product= $this->getOrCreateEntity(Shop::class, "client_id", $discountData[$key]);
                            $discount->$setMethodvalue($product);
                            break;
                        default:
                        $value = $discountData[$key];
                        $discount->$setMethodvalue($value);
                        break;
                    }
                }
            }
            $this->entityManager->persist($discount->getShop());
            $this->entityManager->persist($discount);
            $this->entityManager->flush();
    }

    private function getOrCreateEntity($className,String $field,Mixed $value){
        $entity = $this->entityManager->getRepository($className)->findOneBy([$field => $value]);
        if(!$entity){
            $entity = new $className();
        }
        return $entity;
    }
    
}
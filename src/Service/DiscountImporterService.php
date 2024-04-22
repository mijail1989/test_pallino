<?php

namespace App\Service;

use App\Entity\Shop;
use App\Entity\Discount;
use App\Utils\ImporterUtils;
use App\Service\ProductImporterService;
use Doctrine\ORM\EntityManagerInterface;

class DiscountImporterService
{
    private $entityManager;
    private $importerUtils;
    private $productImporter;

    CONST DISCOUNT_ARRAY_MAPPING = [
        "id" => "setClientId",
        "shop_id" => "setShop",
        "product" => "setProduct",
        "price" => "setPrice",
        "currency" => "setCurrency",
        "description" => "setDescription"
    ];

    public function __construct(EntityManagerInterface $entityManager, ImporterUtils $importerUtils,ProductImporterService $productImporter)
    {   
        $this->entityManager = $entityManager;
        $this->importerUtils = $importerUtils;
        $this->productImporter = $productImporter;
    }
    
    /**
     * Imports discount data into the database.
     *
     * @param array $discountData The discount data to import.
     * @return void
     */
    public function importData(Array $discountData){
        $discount =  $this->importerUtils->getOrCreateEntity(Discount::class, "client_id", $discountData['id']);

        foreach(self::DISCOUNT_ARRAY_MAPPING as $key => $setMethodvalue){
            if(isset($discountData[$key])){

                switch ($key) {
                    case "product":
                        $product = $this->productImporter->importData($discountData[$key]);
                        $discount->setProduct($product);
                        break;
                    case "shop_id":
                        $shop = $this->importerUtils->getOrCreateEntity(Shop::class, "client_id", $discountData[$key]);
                        $shop->setClientId($discountData[$key]);
                        $discount->$setMethodvalue($shop);
                        break;
                    default:
                    $value = $discountData[$key];
                    $discount->$setMethodvalue($value);
                    break;
                }
                
            }
        }
        $this->entityManager->persist($discount);
        $this->entityManager->flush();
    }

}
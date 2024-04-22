<?php

namespace App\Service;

use App\Entity\Shop;
use App\Utils\ImporterUtils;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ShopImporterService
{
    private $entityManager;
    private $logger;
    private $importerUtils;

    CONST SHOP_ARRAY_MAPPING = [
        "id" => "setClientId",
        "name" => "setName",
        "address" => "setAddress",
        "country" => "setCountry" 
    ];

    public function __construct(EntityManagerInterface $entityManager,LoggerInterface $logger, ImporterUtils $importerUtils )
    {   
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->importerUtils = $importerUtils;
    }
    /**
     * Imports shop data into the database.
     *
     * @param array $shopData The shop data to import.
     * @return void
     */
    public function importData(Array $shopData){
        $shop = $this->importerUtils->getOrCreateEntity(Shop::class, "name", $shopData['name']);
        if(!$shop){
            $this->logger->error('An error occurred while creating new Element to Import');
            return;
        }
        foreach(self::SHOP_ARRAY_MAPPING as $key => $setMethodvalue){
            if(isset($shopData[$key])){
                $value = $shopData[$key];
                $shop->$setMethodvalue($value);
            }
        }
        $this->entityManager->persist($shop);
        $this->entityManager->flush();
    }
}
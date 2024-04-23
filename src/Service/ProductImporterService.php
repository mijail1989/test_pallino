<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Entity\Product;
use App\Utils\ImporterUtils;
use Doctrine\ORM\EntityManagerInterface;

class ProductImporterService
{
    private $entityManager;
    private $logger;
    private $importerUtils;

    public function __construct(EntityManagerInterface $entityManager,LoggerInterface $logger, ImporterUtils $importerUtils )
    {   
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->importerUtils = $importerUtils;
    }

    /**
     * Imports product data into the database.
     *
     * @param string $productData The product data to import.
     * @return Product|null The imported product entity or null if an error occurred.
     */
    public function importData(string $productData){
        $product = $this->importerUtils->getOrCreateEntity(Product::class, "name", $productData);
        if(!$product){
            $this->logger->error('An error occurred while creating new Element to Import');
            return;
        }
        $product->setName($productData);

        $values = explode(" - ", $productData);

        switch (count($values)) {
            case 1:
                $product->setCategory("Uncategorized");
                $product->setSubcategory($values[0]);
                break;
            default:
                $product->setCategory($values[0]);
                $subcategories = array_slice($values, 1);
                $subcategory = implode(" - ", $subcategories);
                $product->setSubcategory($subcategory);
                break;
        }
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }
}
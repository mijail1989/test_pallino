<?php
namespace App\Service;

use App\Entity\Discount;
use Doctrine\ORM\EntityManagerInterface;

class DiscountManagerService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {   
        $this->entityManager = $entityManager;
    }

     /**
     * Retrieves discounts associated with a specific shop.
     *
     * @param int $shopID The client_id of the shop.
     * @return array The discounts associated with the specified shop.
     */
    public function getDiscountsByShop(int $shopID):array
    {
        $entity = $this->entityManager->createQueryBuilder()
        ->select('d, s, p') 
        ->from(Discount::class, 'd')
        ->join('d.shop', 's', 'FETCH')
        ->join('d.product', 'p', 'FETCH')
        ->where('s.client_id = :shopID')
        ->setParameter('shopID', $shopID)
        ->orderBy('d.price', 'ASC')
        ->getQuery();

        $discounts = $entity->getResult();
        return $discounts;
    }

        /**
     * Retrieves discounts associated with a specific country.
     *
     * @param string $country The country code.
     * @return array The discounts associated with the specified country.
     */
    public function getDiscountsByCountry(string $country):array
    {
        $entity = $this->entityManager->createQueryBuilder()
        ->select('d, s, p')
        ->from(Discount::class, 'd')
        ->join('d.shop', 's', 'FETCH')
        ->join('d.product', 'p', 'FETCH')
        ->where('s.country = :countryCode')
        ->setParameter('countryCode', $country)
        ->orderBy('d.price', 'ASC')
        ->getQuery();

        $discounts = $entity->getResult();
        return $discounts;
    }
}
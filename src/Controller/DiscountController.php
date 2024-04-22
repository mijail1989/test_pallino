<?php

namespace App\Controller;

use App\Service\DiscountManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscountController extends AbstractController
{
    private $discountManager;
    public function __construct(DiscountManagerService $discountManager)
    {   
        $this->discountManager = $discountManager;
    }

    /**
     * Retrieves Discounts based on the provided parameter.
     *
     * @param mixed $param The parameter to determine the type of Discount retrieval.
     * @return JsonResponse The JSON response containing the Discounts.
     */
    public function getOffers($param)
    {
        if (is_numeric($param)) {
            // If the parameter is a number (presumably shop.client_ID), call the getDiscountsByShop function
            return $this->getDiscountsByShop($param);
        } else {
            // Otherwise, if the parameter is not a number, presumably countryCode, call the getOffersByCountry function
            return $this->getDiscountsByCountry($param);
        }
    }


    /**
     * Retrieves Discounts associated with a specific shop.
     *
     * @param int $shopID The ID of the shop.
     * @return JsonResponse The JSON response containing the offers.
     */
    public function getDiscountsByShop(int $shopID)
    {
    return $this->json($this->discountManager->getDiscountsByShop($shopID));
    }

    /**
     * Retrieves Discounts associated with a specific country.
     *
     * @param string $country The country code.
     * @return JsonResponse The JSON response containing the Discounts.
     */
    public function getDiscountsByCountry(string $country)
    {
        return $this->json($this->discountManager->getDiscountsByCountry($country));
    }

    
}
<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;

class ImporterUtils
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {   
        $this->entityManager = $entityManager;
    }
    /**
     * Retrieves an existing entity from the database or creates a new one if it doesn't exist.
     *
     * @param string $className The class name of the entity.
     * @param string $fieldName The field name used to find the entity.
     * @param mixed $value The value of the field used to find the entity.
     * @return mixed|null The existing or newly created entity, or null if the class doesn't exist.
     */
    public function getOrCreateEntity(string $className,string $fieldName,$value){
        if (!class_exists($className)) {
            return null;
        }
        $entity = $this->entityManager->getRepository($className)->findOneBy([$fieldName => $value]);
        if(!$entity){
            $entity = new $className();
        }
        return $entity;
    }

}
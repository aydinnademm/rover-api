<?php

namespace App\Traits\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

trait EntityManagerServiceTrait
{
    /** @var EntityManagerInterface*/
    protected $_entityManager;

    /**
     * @Required
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return self
     */
    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->_entityManager = $entityManager;

        return $this;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->_entityManager;
    }

    protected function getConnection(): Connection
    {
        return $this->_entityManager->getConnection();
    }

    /**
     * @param $entity
     */
    protected function saveToDatabase($entity): void
    {
        $this->_entityManager->persist($entity);

        $this->_entityManager->flush();
    }
}
<?php

namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class RegistryBase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrineRegistry)
    {
        $this->entityManager = $doctrineRegistry->getManager();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager() : \Doctrine\ORM\EntityManager
    {
        return $this->entityManager;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->getRepository(), $method), $args);
    }

    protected function getRepository() :  \Doctrine\ORM\EntityRepository
    {
        if(!$this->repository) {
            $this->repository = $this->entityManager->getRepository($this->entityQualifiedName());
        }
        return $this->repository;
    }

    public function saveOrUpdate($entity)
    {
        $merged = $this->entityManager->merge($entity);
        $this->entityManager->persist($merged);
        $this->entityManager->flush();
        return $merged;
    }

    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }



    public function truncateDb($tableName = null)
    {
        if(!$tableName) {
            $tableName = $this->tableName();
        }
        $connection = $this->getEntityManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeQuery("SET FOREIGN_KEY_CHECKS = 0; ");
        $connection->executeUpdate($platform->getTruncateTableSQL($tableName, true /* whether to cascade */));
        $connection->executeQuery("SET FOREIGN_KEY_CHECKS = 1; ");
    }

    protected abstract function entityQualifiedName(): string;

    protected abstract function tableName() : string;
}
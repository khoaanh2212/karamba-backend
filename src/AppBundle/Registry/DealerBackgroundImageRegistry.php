<?php


namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;


class DealerBackgroundImageRegistry extends RegistryBase
{

    public function saveOrUpdate($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    protected function entityQualifiedName(): string
    {
        return "AppBundle:DealerBackgroundImage";
    }

    protected function tableName() : string
    {
        return "dealersbackgroundimage";
    }
}
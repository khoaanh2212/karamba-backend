<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 27/07/16
 * Time: 12:12
 */

namespace AppBundle\Registry;


use AppBundle\Utils\RegistryBase;

class AvatarRegistry extends RegistryBase
{

    public function saveOrUpdate($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    protected function entityQualifiedName(): string
    {
        return "AppBundle:Avatar";
    }

    protected function tableName() : string
    {
        return "avatars";
    }
}
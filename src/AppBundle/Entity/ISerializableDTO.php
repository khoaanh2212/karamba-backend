<?php

namespace AppBundle\Entity;


interface ISerializableDTO
{
    public function toDTO();
}
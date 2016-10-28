<?php

namespace AppBundle\Utils;
use Ramsey\Uuid\Uuid;

class UUIDGenerator
{
    public function generateId() : string {
        return Uuid::uuid4()->toString();
    }
}
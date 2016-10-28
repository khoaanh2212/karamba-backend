<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 22/08/16
 * Time: 16:47
 */

namespace AppBundle\DomainServices;

use AppBundle\Entity\Client;
use AppBundle\Registry\ClientRegistry;
use AppBundle\Utils\Point;
use Doctrine\ORM\EntityNotFoundException;

class ClientDomainService
{
    /**
     * @var ClientRegistry
     */
    private $registry;

    public function __construct(ClientRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function create(string $name, string $email, string $zipCode, string $city, string $password, Point $position): Client
    {
        $dealer = new Client($name, $email, $zipCode, $city, $password, $position);
        return $this->registry->saveOrUpdate($dealer);
    }

    public function updateClient(string $id, string $name = null, string $zipCode = null, string $password = null, Point $position = null) {
        $client = $this->findById($id);
        if($name){
            $client->setName($name);
        }
        if ($password) {
            $client->setPassword($password);
        }
        if ($zipCode) {
            $client->setZipcode($zipCode);
        }
        if($position) {
            $client->setPosition($position);
        }
        $client = $this->registry->saveOrUpdate($client);
        return $client;
    }

    /**
     * @param string $id
     * @throws EntityNotFoundException
     * @return Client
     */
    public function findById(string $id)
    {
        $client = $this->registry->findOneById($id);
        if (!$client) {
            throw new EntityNotFoundException();
        }
        return $client;
    }
}
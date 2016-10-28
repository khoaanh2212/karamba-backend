<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 24/08/16
 * Time: 15:20
 */

namespace AppBundle\Repository;


use AppBundle\DTO\AppliancesForDealersDetailDTO;
use AppBundle\DTO\AppliancesForDealersDTO;
use AppBundle\Utils\ApplianceOfferState;
use Doctrine\ORM\EntityRepository;

class CarApplianceRepository extends EntityRepository
{
    public function findApplianceOffersForDealer(string $dealerId)
    {
        $sql = "select * from appliancesfordealers where dealer_id='$dealerId'";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        $results = $sqlStatement->fetchAll();
        $data = array();
        foreach ($results as $result) {
            $id = $result["id"];
            $clientName = $result["clientName"];
            $clientEmail = $result["clientEmail"];
            $brand = $result["brand"];
            $model = $result["model"];
            $price = json_decode($result["price"]);
            $package = json_decode($result["package"]);
            $color = json_decode($result["color"]);
            $extras = json_decode($result["extras"]);
            $numberOfOffers = $result["number_of_offers"];
            $vehicleId = $result["vehicle_id"];
            $isRead = $result["is_read"];
            $state = $result["state"];
            if (!$state) {
                $state = ApplianceOfferState::NEW_OPPORTUNITY;
            }
            $created = $result["created"];
            array_push($data, new AppliancesForDealersDTO($id, $clientName, $clientEmail, $brand, $model, $price, $extras, $numberOfOffers, $vehicleId, $dealerId, $isRead, $state, $created, $package, $color));
        }
        return $data;
    }

    public function findOneApplianceOffer(string $id)
    {
        $sql = "select id, offer_id, created, state, city,clientName,clientEmail,ASTEXT(clientPosition) as p ,brand,model,price,package,color,extras,number_of_offers,vehicle_id,dealer_id,dealer_name,derivative,transmission,motor_type,number_of_doors from appliancesfordealers where id='$id'";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        $results = $sqlStatement->fetchAll();
        $result = $results[0];
        $id = $result["id"];
        $offerId = $result["offer_id"];
        $clientName = $result["clientName"];
        $state = $result["state"];
        if (!$state) {
            $state = ApplianceOfferState::NEW_OPPORTUNITY;
        }
        $created = $result["created"];
        $clientEmail = $result["clientEmail"];
        $brand = $result["brand"];
        $model = $result["model"];
        $price = json_decode($result["price"]);
        $package = json_decode($result["package"]);
        $color = json_decode($result["color"]);
        $extras = json_decode($result["extras"]);
        $numberOfOffers = $result["number_of_offers"];
        $vehicleId = $result["vehicle_id"];
        $dealerId = $result["dealer_id"];
        $dealerName = $result["dealer_name"];
        $derivative = $result["derivative"];
        $transmission = $result["transmission"];
        $motorType = $result["motor_type"];
        $numberOfDoors = $result["number_of_doors"];
        $position = $result["p"];
        $city = $result["city"];
        list($longitude, $latitude) = sscanf($position, 'POINT(%f %f)');
        return new AppliancesForDealersDetailDTO($id, $offerId, $state, $created, $clientName, $clientEmail, $city, $longitude, $latitude, $brand, $model, $derivative, $transmission, $motorType, $numberOfDoors, $price, $extras, $numberOfOffers, $vehicleId, $dealerId, $dealerName, $package, $color);
    }

    public function findListOfferHaveAtLeastOneMessageFromClientByClientId($clientId)
    {
        $sql = "select offer_id
                from appliancesforclients
                where client_id = '" . $clientId . "'
                        and message_author_type is not null
                group by offer_id";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        $results = $sqlStatement->fetchAll();
        $data = array();
        foreach ($results as $item) {
            $query = "select client_id, offer_id,dealer_name,dealer_address,model,dealer_avatar,
                          (select count(*) from appliancesforclients where offer_id=ac.offer_id) as number_message,
                          message_created as last_message
                        FROM appliancesforclients ac
                        WHERE offer_id='" . $item['offer_id'] . "'
                        ORDER BY message_created DESC
                        LIMIT 1";
            $sqlStatement = $this->getEntityManager()->getConnection()->prepare($query);
            $sqlStatement->execute();
            $record = $sqlStatement->fetchAll();
            $record[0]['dealer_avatar'] = $record[0]['dealer_avatar'] != "" ? "/images/avatars/" . $record[0]['dealer_avatar'] : "";
            $record[0]['now'] = date('Y-m-d h:i:s');
            array_push($data, $record[0]);
        }
        return $data;
    }

    public function findOffersDealersNameFromClientByClientId($clientId)
    {
        $sql = "SELECT DISTINCT (afc.dealer_id), afc.dealer_name
                FROM appliancesforclients AS afc
                WHERE afc.client_id = '" . $clientId . "' AND afc.dealer_id NOT IN (SELECT dealer_id FROM reviews WHERE client_id = afc.client_id)
                ORDER BY afc.dealer_name DESC";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        $results = $sqlStatement->fetchAll();
        $data = array();
        foreach ($results as $result) {
            array_push($data, $result);
        }

        return $data;
    }

    public function findApplianceOffersArchivedForDealer($dealerId)
    {
        $sql = "select * from appliancesfordealers 
                WHERE dealer_id='$dealerId' AND (`state`='won' OR `state`='closed' OR `state`='offer_expired')";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        $results = $sqlStatement->fetchAll();
        $data = array();
        foreach ($results as $result) {
            $id = $result["id"];
            $clientName = $result["clientName"];
            $clientEmail = $result["clientEmail"];
            $brand = $result["brand"];
            $model = $result["model"];
            $price = json_decode($result["price"]);
            $package = json_decode($result["package"]);
            $color = json_decode($result["color"]);
            $extras = json_decode($result["extras"]);
            $numberOfOffers = $result["number_of_offers"];
            $vehicleId = $result["vehicle_id"];
            $isRead = $result["is_read"];
            $state = $result["state"];
            $created = $result["created"];
            array_push($data, new AppliancesForDealersDTO($id, $clientName, $clientEmail, $brand, $model, $price, $extras, $numberOfOffers, $vehicleId, $dealerId, $isRead, $state, $created, $package, $color));
        }
        return $data;
    }

    public function findOffersHasAtLeastOneMessageForDealer($dealerId)
    {
        $sql = "SELECT DISTINCT (afc.offer_id)
                FROM appliancesforclients AS afc
                WHERE afc.dealer_id = '" . $dealerId . "' AND afc.message_author_type <> ''";
        $sqlStatement = $this->getEntityManager()->getConnection()->prepare($sql);
        $sqlStatement->execute();
        return $sqlStatement->fetchAll();
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 27/07/16
 * Time: 18:35
 */

namespace AppBundle\DTO;


use Symfony\Component\DomCrawler\Image;

class DealerWithConditionsDTO
{
    /**
     * @var DealerDTO
     */
    public $profile;

    /**
     * @var DealerConditionDTO[]
     */
    public $conditions;

    /**
     * @var string
     */
    public $avatar;

    /**
     * @var string
     */
    public $background;

    public function __construct(DealerDTO $profile, array $conditions)
    {
        $this->profile = $profile;
        $this->conditions = $conditions;
    }

    public function addAvatar(ImageDTO $dto)
    {
        $this->avatar = $dto->url;
    }

    public function addBackgroundImage(ImageDTO $dto)
    {
        $this->background = $dto->url;
    }
}
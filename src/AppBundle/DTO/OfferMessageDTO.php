<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 1/09/16
 * Time: 13:20
 */

namespace AppBundle\DTO;


use DateTime;

class OfferMessageDTO
{
    /**
     * @var string
     */
    public $type;
    /**
     * @var ImageDTO
     */
    public $image;
    /**
     * @var string
     */
    public $content;
    /**
     * @var string
     */
    public $sendDate;
    /**
     * @var array
     */
    public $downloads;


    public function __construct(string $type, string $content, DateTime $date)
    {
        $this->type = $type;
        $this->content = $content;
        $this->sendDate = $date->format('Y-m-d H:i:s');
        $this->downloads = array();
    }

    public function addImage(ImageDTO $dto) {
        $this->image = $dto;
    }

    public function addAttachment(ImageDTO $dto)
    {
        array_push($this->downloads, $dto);
    }


}

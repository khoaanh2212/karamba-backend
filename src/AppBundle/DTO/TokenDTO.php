<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 20/07/16
 * Time: 14:50
 */

namespace AppBundle\DTO;

class TokenDTO
{
    /**
     * @var string
     */
    public $token;

    public $email;

    /**
     * @var \DateTime
     */
    public $expirationDate;

    public function __construct(string $token, string $email, \DateTime $expirationDate)
    {
        $this->token = $token;
        $this->email = $email;
        $this->expirationDate = $expirationDate;
    }
}
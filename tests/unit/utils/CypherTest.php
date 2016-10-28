<?php


use AppBundle\Utils\Cypher;

class CypherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cypher
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new Cypher();
    }

    public function test_encrypt_willEncryptTheText()
    {
        $text = "clean";
        $actual = $this->sut->encrypt($text);
        $this->assertNotEquals($text, $actual);
    }

    public function test_verify_valid_shouldReturnTrue()
    {
        $text = "clean";
        $cypher = $this->sut->encrypt($text);
        $this->assertTrue($this->sut->verify($text, $cypher));
    }

    public function test_verify_invalid_returnFalse()
    {
        $text = "clean";
        $cypher = $this->sut->encrypt($text);
        $this->assertFalse($this->sut->verify("notthesame", $cypher));
    }
}
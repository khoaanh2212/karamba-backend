<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 26/07/16
 * Time: 18:45
 */

namespace AppBundle\Utils;


use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class CustomFileNamer implements NamerInterface
{

    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;

    public function __construct()
    {
        $this->uuidGenerator = UUIDGeneratorFactory::getInstance();
    }

    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to.
     * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The file name.
     */
    public function name($object, PropertyMapping $mapping)
    {
        $extension =  $mapping->getFile($object)->getClientOriginalExtension();
        return $this->uuidGenerator->generateId().".".$extension;
    }
}
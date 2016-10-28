<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 16/10/16
 * Time: 0:06
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class AttachmentsController extends Controller
{
    /**
     * @Route("/attachment/{fileName}")
     * @Method({"GET"})
     * @param $fileName
     * @return JsonResponse
     *
     */
    public function downloadAttachment($fileName)
    {
        $naturalFileName = explode("_", $fileName)[1];
        $path = $this->get('kernel')->getRootDir(). "/../web/attachments/";
        $content = file_get_contents($path.$fileName);
        $response = new Response();
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$naturalFileName);
        $response->setContent($content);
        return $response;
    }
}
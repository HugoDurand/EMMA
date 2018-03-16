<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $data = $request->request->get('download');
        define('APPLICATION_PATH', realpath(__DIR__) . '/');
        define('WEB_PATH',  APPLICATION_PATH . '../web/uploads');

        if(isset($data)) {
            $date = new \DateTime();
            $date = $date->format('d-m-Y_H:i:s');
            $pdfPath = $this->getParameter('dir.downloads').'/Example.xlsx';
            return $this->file($pdfPath, $date.'.xlsx');

        }

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/ajax_snippet_image_send", name="ajax_snippet_image_send")
     */
    public function ajaxSnippetImageSendAction(Request $request)
    {

        $session = $request->getSession();
        $dynamiqueFolder = $this->getUser()->getId().$this->getUser()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $document = new Document();
        $media = $request->files->get('file');
        $document->setFile($media);
        $document->setPath(__DIR__.'/../../../web/uploads/documents/'.$this->getUser()->getId().$this->getUser()->getUsername().'/');
        $document->setName($media->getClientOriginalName());
        $document->upload($dynamiqueFolder);

        //$em->persist($pmail);
        //$em->flush();


        $session->set('media',$document );


    }


}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class BuildMailController extends Controller
{
    /**
     * @Route("/build", name="build")
     */
    public function indexAction(Request $request)
    {

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        //var_dump($this->getUser()->getId());
        $rows = $session->get('rows');
        $data = $request->request->get("editordata");
        $subject = $request->request->get("subject");
        $from = $request->request->get("from");
        $alias = $request->request->get("alias");
        if(!empty($alias)){
            $alias = htmlspecialchars($alias);
        }

        $mail = array();
        for ($i = 1; $i < count($rows);$i++){
            if(isset($data)){
                $content = str_replace("%%nom%%", $rows[$i][0], $data);
                $content = str_replace("%%prenom%%", $rows[$i][1], $content);
                $content = str_replace("%%login%%", $rows[$i][3], $content);
                $content = str_replace("%%password%%", $rows[$i][4], $content);
               // $mail["from"] = $from;
               // $mail["subject"] = $subject;
               // $mail["content"] = $content;
               // $mail["to"] = $rows[$i][2];
                $mail[]=array("from"=>$from, "subject"=>$subject, "content"=>$content, "to"=>$rows[$i][2], "alias"=>$alias);

                $pmail = new Mail();
                $pmail->setUserId($this->getUser()->getId());
                $pmail->setEmailFrom($from);
                $pmail->setEmailTo($rows[$i][2]);
                $pmail->setEmailSubject($subject);
                $pmail->setEmailContent($content);
                $pmail->setDate(new \DateTime());
                $pmail->setSend(0);

                $em->persist($pmail);
                $em->flush();
            }

        }

        $session->set('mails',$mail );
        return $this->render('AppBundle:BuildMail:build.html.twig', array(
            // ...
        ));
    }

}

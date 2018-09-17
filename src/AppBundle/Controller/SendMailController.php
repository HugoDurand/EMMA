<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class SendMailController extends Controller
{
    /**
     * @Route("send", name="send")
     */
    public function indexAction(Request $request, \Swift_Mailer $mailer)
    {

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $mails = $session->get('mails');
        $file = $session->get('media');
        $date = new \DateTime;
        $date = $date->format('d-m-Y_H-i-s');
        $data = $request->request->get('send');
        $verifmail = 0;

        foreach ($mails as $mail) {
            $pmail = $em->getRepository(Mail::class)->findBy(['emailFrom'=>$mail['from'], 'emailTo'=>$mail['to'], 'emailSubject'=>$mail['subject'], 'send'=>0 ,'emailContent'=>$mail['content'], 'userId'=>$this->getUser()->getId()]);

            $message = (new \Swift_Message($mail['subject']))
                ->setFrom($mail['from'], $mail['alias'])
                ->setTo($mail['to'])
                ->setBcc('hugo.durand@viaaduc.com')
                ->setBody($mail['content'], 'text/html'
                );

            if(isset($data)) {
                $mailer->send($message);
                $verifmail++;

                $pmail[0]->setSend(1);
                $em->flush();
                //$fileSystem = new Filesystem();
                //$fileSystem->rename($file->getPath().$file->getName(), $file->getPath().'('.$date.')'.$file->getName());
            }

        }

        if($verifmail == count($mails)){
            $session->invalidate();
            //var_dump($verifmail);
            return $this->redirectToRoute('homepage');
        }else{
            return $this->render('AppBundle:SendMail:send.html.twig', array(
                // ...
            ));
        }
    }

}

<?php

namespace ZEN\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use ZEN\MessageBundle\Form\MessageAdminType;
use ZEN\MessageBundle\Entity\Discussion;
use ZEN\MessageBundle\Entity\Message;
use ZEN\MessageBundle\Form\MessageReplyType;

class AdminMessageController extends Controller {

    public function addMessageAction(Request $request, $id_discussion = 0) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $message = new Message();

        //Réponse
        if ($id_discussion) {
            $form = $this->get('form.factory')->create(new MessageReplyType(), $message, array(
                'action' => $this->generateUrl('zen_admin_add_message', array('id_discussion' => $id_discussion))
            ));
        }
        //Nouvelle discussion
        else {
            $model = $this->container->getParameter('zen_message.model_class_group');
            $form = $this->get('form.factory')->create(new MessageAdminType($model), $message, array(
                'action' => $this->generateUrl('zen_admin_add_message')
            ));
        }
        //Gere la soumission
        if ($form->handleRequest($request)->isValid()) {
            $dateSend = new \DateTime("now");
            //Réponse à un message
            if ($id_discussion) {
                //récup le destinataire
                $discussion = $em->getRepository('ZENMessageBundle:Discussion')->find($id_discussion);
                $receiver = $discussion->getGroup()->getUser();
                //Passe le status de READ => UNREAD
                $discussion->setIsRead(0);
            }
            //Nouvelle discussion
            else {
                //Le receiver sera l'utilisateur lié au groupe (exemple: hotel) sélectionné
                $group = $form->get('group')->getData();
                $receiver = $group->getUser();
                $discussion = new Discussion();
                $discussion->setGroup($group);
                $discussion->setAuthor($user);
                $discussion->setCatMessage($form->get('catMessage')->getData());
                $discussion->setSubject($form->get('subject')->getData());
            }

            
            $discussion->setDateUpdate($dateSend);
            
            $message->setDiscussion($discussion);
            $message->setSender($user);
            $message->setReceiver($receiver);
            $message->setDateSend($dateSend);

            $em->persist($message);
            $em->flush();
            
            if ($request->isXmlHttpRequest()) {
                $response = new Response();
                $output = array('type' => 'success', 'content' => 'message_add.success');
                $response->headers->set('Content-Type', 'application/json');
                $response->setContent(json_encode($output));
                return $response;
            } else {
                $url = $this->get('router')->generate('zen_admin_list_discussion');
                return new RedirectResponse($url);
            }
        }
        return $this->render('ZENMessageBundle::message.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function listDiscussionAction(Request $request, $id_cat_message = 0) {
        $em = $this->getDoctrine()->getManager();

        //Discussions archivés
        if ($id_cat_message == "-1") {
            $discussions = $em->getRepository('ZENMessageBundle:Discussion')->findBy(array('archivedAdmin' => 1), array('dateUpdate' => 'DESC'));
        } else {
            $discussions = $em->getRepository('ZENMessageBundle:Discussion')->findBy(array('archivedAdmin' => 0), array('dateUpdate' => 'DESC'));
        }

        $nbNewDiscussions = $em->getRepository('ZENMessageBundle:Discussion')->countNewDiscussions();
        //Nombre de discussion
//        $nbDiscussions = $em->getRepository('ZENMessageBundle:Discussion')->countDiscussions();

        $listCatMessage = array(0 => "Tout les messages", "-1" => "Archivés");

        return $this->render('ZENMessageBundle:Admin:list-discussion.html.twig', array(
                    'discussions' => $discussions,
//                    'nbDiscussions' => $nbDiscussions,
                    'catMessages' => $listCatMessage,
                    'currentCat' => $id_cat_message,
                    'nbNewDiscussions' => $nbNewDiscussions
        ));
    }

    public function setIsReadDiscussionAction(Request $request, $id_discussion) {
        $em = $this->getDoctrine()->getManager();

        $discussion = $em->getRepository('ZENMessageBundle:Discussion')->find($id_discussion);
        //Passe le status de UNREAD => READ
        $discussion->setIsReadAdmin(1);
        $em->persist($discussion);
        $em->flush();

        if ($request->isXmlHttpRequest()) {

            $response = new Response();
            $output = array('success' => true, 'content' => 'message.read');
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));
            return $response;
        } else {
            $url = $this->get('router')->generate('zen_admin_list_discussion');
            return new RedirectResponse($url);
        }
    }

    public function archivedDiscussionAction(Request $request, $id_discussion) {
        $em = $this->getDoctrine()->getManager();

        $discussion = $em->getRepository('ZENMessageBundle:Discussion')->find($id_discussion);

        if ($discussion) {
            if ($discussion->getArchivedAdmin()) {
                $discussion->setArchivedAdmin(0);
            } else {
                $discussion->setArchivedAdmin(1);
            }
            $em->persist($discussion);
            $em->flush();
        }

        if ($request->isXmlHttpRequest()) {
            $response = new Response();
            $output = array('success' => true, 'content' => 'message.archived');
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));
            return $response;
        } else {
            $url = $this->get('router')->generate('zen_admin_list_discussion');
            return new RedirectResponse($url);
        }
    }

}

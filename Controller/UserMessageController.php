<?php

namespace ZEN\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use ZEN\MessageBundle\Form\MessageType;
use ZEN\MessageBundle\Entity\Discussion;
use ZEN\MessageBundle\Entity\Message;
use ZEN\MessageBundle\Form\MessageReplyType;

class UserMessageController extends Controller {

    public function addMessageAction(Request $request, $id_discussion = 0) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $message = new Message();

        if ($id_discussion) {
            $form = $this->get('form.factory')->create(new MessageReplyType(), $message, array(
                'action' => $this->generateUrl('zen_mes_add_message', array('id_discussion' => $id_discussion))
            ));
        } else {
            $form = $this->get('form.factory')->create(new MessageType(), $message, array(
                'action' => $this->generateUrl('zen_mes_add_message')
            ));
        }

        //Gere la soumission
        if ($form->handleRequest($request)->isValid()) {
            // Réponse
            if ($id_discussion) {
                $discussion = $em->getRepository('ZENMessageBundle:Discussion')->find($id_discussion);
                $discussion->setIsReadAdmin(0);
            }
            // Nouvelle discussion
            else {
                $catMessage = $form->get('catMessage')->getData();
                $subject = $form->get('subject')->getData();

                $discussion = new Discussion();
                $discussion->setCatMessage($catMessage);
                $discussion->setSubject($subject);
                $discussion->setAuthor($user);

                //On recupere la fonction getGroup liée à user (exemple: getHotel())
                $getGroup = $this->getGroup();
                $discussion->setGroup($user->$getGroup());
            }

            $dateSend = new \DateTime("now");
            $discussion->setDateUpdate($dateSend);
            $message->setDiscussion($discussion);
            $message->setSender($user);
            $message->setDateSend($dateSend);

            $validator = $this->container->get('validator');
            $e = $validator->validate($message);

            if ($request->isXmlHttpRequest()) {
                //Erreur
                if (count($e) > 0) {
                    $response = new Response();
                    $output = array('type' => 'error', 'content' => 'message_add.error');
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setContent(json_encode($output));
                    return $response;
                } else {

                    $em->persist($message);
                    $em->flush();

                    $response = new Response();
                    $output = array('type' => 'success', 'content' => 'message_add.success');
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setContent(json_encode($output));
                    return $response;
                }
            } else {
                $url = $this->get('router')->generate('zen_mes_list_discussion');
                return new RedirectResponse($url);
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                $response = new Response();
                $output = array('type' => 'error', 'content' => 'message_add.error');
                $response->headers->set('Content-Type', 'application/json');
                $response->setContent(json_encode($output));
                return $response;
            }
        }

        return $this->render('ZENMessageBundle::message.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function listDiscussionAction(Request $request, $id_cat_message = 0) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $archived = 0;
        $admin = 1;
        $idUser = $user->getId();
        $getGroup = $this->getGroup();
        $group = $user->$getGroup();
        $idGroup = $group->getId();

        //Affiche les messages en fonction de la catégorie
        if ($id_cat_message >= 0) {
            $discussions = $em->getRepository('ZENMessageBundle:Discussion')->getDiscussionsGroup($idGroup, $id_cat_message, $archived, $idUser);
        }
        //Afficher les messages archivés
        elseif ($id_cat_message == "-1") {
            $archived = 1;
            $discussions = $em->getRepository('ZENMessageBundle:Discussion')->getDiscussionsGroup($idGroup, $id_cat_message, $archived);
        }
        //Liste les message LikeInn
        else {
            $discussions = $em->getRepository('ZENMessageBundle:Discussion')->getDiscussionsGroup($idGroup, $id_cat_message, $archived, $idUser, $admin);
        }

        $catMessages = $em->getRepository('ZENMessageBundle:CatMessage')->findAll();

        //créer la liste des catégories pour la vue
        $listCatMessage = array(0 => "Tout les messages");
        foreach ($catMessages as $cm) {
            $name = $cm->getName();
            if (empty($name)) {
                $name = $cm->getDevAlias();
            }
            $listCatMessage[$cm->getId()] = $name;
        }

        $listCatMessage["-1"] = "Archivés";
        $listCatMessage["-2"] = "Hall-Inn";

        $nbNewDiscussions = $em->getRepository('ZENMessageBundle:Discussion')->countNewDiscussions($idGroup, $idUser);
        //Nombre de discussion
//        $nbDiscussions = $em->getRepository('ZENMessageBundle:Discussion')->countDiscussions($idGroup);

        return $this->render('ZENMessageBundle:User:list-discussion.html.twig', array(
                    'discussions' => $discussions,
                    'catMessages' => $listCatMessage,
                    'currentCat' => $id_cat_message,
//                    'nbDiscussions' => $nbDiscussions,
                    'nbNewDiscussions' => $nbNewDiscussions,
        ));
    }

    public function setIsReadDiscussionAction(Request $request, $id_discussion) {
        $em = $this->getDoctrine()->getManager();

        $discussion = $em->getRepository('ZENMessageBundle:Discussion')->find($id_discussion);
        //Passe le status de UNREAD => READ
        $discussion->setIsRead(1);
        $em->persist($discussion);
        $em->flush();

        if ($request->isXmlHttpRequest()) {

            $response = new Response();
            $output = array('success' => true, 'content' => 'message.read');
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));
            return $response;
        } else {
            $url = $this->get('router')->generate('zen_mes_list_discussion');
            return new RedirectResponse($url);
        }
    }

    public function archivedDiscussionAction(Request $request, $id_discussion) {
        $em = $this->getDoctrine()->getManager();

        $discussion = $em->getRepository('ZENMessageBundle:Discussion')->find($id_discussion);

        if ($discussion) {
            if ($discussion->getArchived()) {
                $discussion->setArchived(0);
            } else {
                $discussion->setArchived(1);
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
            $url = $this->get('router')->generate('zen_mes_list_discussion');
            return new RedirectResponse($url);
        }
    }

//On recupere la fonction getGroup liée à user (exemple: getHotel())
    protected function getGroup() {
        $em = $this->getDoctrine()->getManager();
        $model_user = $this->container->getParameter('zen_message.model_class_user')['model_class_user'];
        $model_group = $this->container->getParameter('zen_message.model_class_group')['model_class_group'];
        $metaData = $em->getClassMetadata($model_user);
        $associationMappings = $metaData->getAssociationMappings();

        foreach ($associationMappings as $assoc) {
            if ($assoc['targetEntity'] == $model_group) {
                $getGroup = 'get' . ucfirst($assoc['fieldName']);
            }
        }
        if (isset($getGroup)) {
            return $getGroup;
        } else {
            return null;
        }
    }

}

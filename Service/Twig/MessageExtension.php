<?php

namespace ZEN\MessageBundle\Service\Twig;

class MessageExtension extends \Twig_Extension {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function getFunctions() {
        return array(
            'countNewDiscussion' => new \Twig_Function_Method($this, 'countNewDiscussion'),
        );
    }

    public function getName() {
        return 'zen.mesbundle.twig_extension';
    }

    public function countNewDiscussion() {
        $nbNewDiscussion = 0;
        $repo = $this->container->get('doctrine')->getManager()->getRepository('ZENMessageBundle:Discussion');
        $user = $this->container->get('security.context')->getToken()->getUser();
        $getGroup = $this->getGroup();
        $group = $user->$getGroup();
        if ($group) {
            $nbNewDiscussion = $repo->countNewDiscussions($group->getId());
        }
        return $nbNewDiscussion;
    }

    protected function getGroup() {
        $em = $this->container->get('doctrine')->getManager();
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

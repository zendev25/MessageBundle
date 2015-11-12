<?php

namespace ZEN\MessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageReplyType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->remove('catMessage')
                ->remove('subject')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function getParent() {
        return new MessageType();
    }

    /**
     * @return string
     */
    public function getName() {
        return 'zen_mes_form_messageReply';
    }

}

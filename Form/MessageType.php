<?php

namespace ZEN\MessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('catMessage', 'entity', array(
                    'class' => 'ZENMessageBundle:CatMessage',
                    'mapped' => false,
                    'label' => 'catMessage',
                    'empty_value' => 'catMessage.select',
                    'attr' => ['class' => 'select-search']
                ))
                ->add('subject', 'text', ['label' => 'subject', 'mapped' => false])
                ->add('content', 'textarea', ['label' => 'content'])
//                ->add('save',  'submit', [
//                    'attr'=>['class' =>'button right modal-submit']
//                ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ZEN\MessageBundle\Entity\Message',
            'translation_domain' => 'form_message'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'zen_mes_form_message';
    }

}

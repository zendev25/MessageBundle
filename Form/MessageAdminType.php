<?php

namespace ZEN\MessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageAdminType extends AbstractType {

    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('group', 'entity', array(
                    'class' => $this->model['model_class_group'],
                    'query_builder' => function($em) {
                        return $em->createQueryBuilder('c');
                    },
                    'mapped' => false,
                    'label' => 'group')
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function getParent() {
        return new MessageType();
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'translation_domain' => 'form_message'
        ));
    }
    
    /**
     * @return string
     */
    public function getName() {
        return 'zen_mes_form_messageAdmin';
    }

}

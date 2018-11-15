<?php

namespace VisageFour\Bundle\PersonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserRegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            TextType::class,
            array(
                'label'                 => 'Email',
                'required'              => true,
                'attr'                  => array(
                    'maxlength'             => 75,
                    'placeholder'           => 'Email address'
                )
            )
        )->add(
            'plainPassword',
            RepeatedType::class,
            array(
                'type'                  => PasswordType::class,
                'invalid_message'       => 'The password fields must match.',
                'options'               => array(
                    'attr' => array(
                        'class'             => 'password-field',
                        'maxlength'         => 30,
                        'placeholder'       => ''
                    )
                ),
                'required'              => true,
                'first_options'         => array('label' => 'Password'),
                'second_options'        => array('label' => 'Repeat Password'),
            )
        )->add(
            'submit',           SubmitType::class,
            array(
                'label'         => 'Register',
                'attr'          => array('class' => 'pull-left pri-button')
            )
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    static public function setDefaultData (Form $form) {
        $form->get('email')->setData('cameronrobertburns@gmail.com');
        $form->get('plainPassword')->setData('cameron');
        //dump($form); die();

        //get('plainPassword')->setData('cameron');
    }
}
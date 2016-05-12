<?php

namespace VisageFour\PersonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasePersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                ChoiceType::class,
                array(
                    'label'                 => 'Title',
                    'choices'  => array(
                        'Please select one' => 'default',
                        'Mr'                => 'mr',
                        'Ms'                => 'ms',
                        'Mrs'               => 'mrs',
                    ),
                    'choices_as_values'     => true
                )
            )
            ->add(
                'firstName',
                TextType::class,
                array(
                    'label'                 => 'First name',
                    'required'              => true,
                    'attr'                  => array(
                        'maxlength'             => 20,
                        'placeholder'           => 'First name'
                    )
                )
            )
            ->add(
                'lastName',
                TextType::class,
                array(
                    'label'                 => 'Last name',
                    'required'              => true,
                    'attr'                  => array (
                        'maxlength'             => 20,
                        'placeholder'           => 'Last name'
                    )
                )
            )
            ->add(
                'lastName',
                TextType::class,
                array(
                    'label'                 => 'username',
                    'required'              => true,
                    'attr'                  => array (
                        'maxlength'             => 255,
                        'placeholder'           => 'Username'
                    )
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'                 => 'Email address',
                    'required'              => true,
                    'attr'                  => array (
                        'maxlength'             => 75,
                        'placeholder'           => 'Email address'
                    )
                )
            )
            ->add(
                'mobileNumber',
                TextType::class,
                array(
                    'label'                 => 'Mobile Number',
                    'required'              => true,
                    'attr'                  => array (
                        'maxlength'             => 75,
                        'placeholder'           => 'Mobile Number'
                    )
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label'                 => 'Submit',
                    'attr'                  => array(
                        'class' => 'pull-left'
                    )
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VisageFour\PersonBundle\Entity\BasePerson'
        ));
    }
}
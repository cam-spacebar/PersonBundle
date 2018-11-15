<?php

namespace VisageFour\Bundle\PersonBundle\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface BaseFormTypeInterface
{
    // base form methods:
    public function createForm (Request $request, $options);
    public function getForm();
    public function setProcessingResult();
    public function throwFormResultCodeError();
    public function setFormResult();

    // implemented form methods.
    public function buildForm(FormBuilderInterface $builder, array $options);
    public function configureOptions(OptionsResolver $resolver);
    public function setFormTestDefaults();
    public function handleFormSubmission();

}
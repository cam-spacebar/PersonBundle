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
    public function setProcessingResult($result);
    public function throwFormResultCodeError($formResult);
    public function setFormResult($formResultCode);

    // super class form methods.
    public function buildForm(FormBuilderInterface $builder, array $options);
    public function configureOptions(OptionsResolver $resolver);
//    protected function setFormTestDefaults();      cannot implement protected methods.
//    protected function getDefaultData();

    public function handleFormSubmission();
}
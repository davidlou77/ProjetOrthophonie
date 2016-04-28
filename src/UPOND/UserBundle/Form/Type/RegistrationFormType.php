<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 23/04/2016
 * Time: 19:20
 */

namespace UPOND\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('Nom', TextType::class);
        $builder->add('Prenom', TextType::class);
    }

    public function getName()
    {
        return 'upond_user_registration';
    }
}
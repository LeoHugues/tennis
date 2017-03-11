<?php

/**
 * Created by PhpStorm.
 * User: julien_mathe
 * Date: 02/03/2017
 * Time: 15:55
 */

namespace UserBundle\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseFormType;
use UserBundle\Entity\User;

class RegistrationAdminType extends BaseFormType
{
    private $class;

    /**
     * @param string $class The Group class name
     */
    public function __construct()
    {
        $this->class = User::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('roleString', ChoiceType::class, array('label' => 'Role : ',
                'choices' => array(
                    'Utilisateur' => 'ROLE_USER',
                    'Presse' => 'ROLE_PRESS',
                    'Organisation' => 'ROLE_ORGA',
                    'Administrateur' => 'ROLE_ADMIN',
                )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'group',
        ));
    }

    // BC for SF < 2.7
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function getBlockPrefix()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'user_registration_admin';
    }
}

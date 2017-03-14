<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 3/14/17
 * Time: 10:04 AM
 */

namespace UserBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseFormType;
use OrganisationBundle\Entity\Arbitre;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationArbitreType extends BaseFormType
{
    private $class;

    /**
     * @param string $class The Group class name
     */
    public function __construct()
    {
        $this->class = Arbitre::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
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
        return 'arbitre_registration';
    }
}

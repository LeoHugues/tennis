<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2/21/17
 * Time: 9:52 AM
 */

namespace OrganisationBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OrganisationBundle\Entity\Equipe;
use OrganisationBundle\Entity\Joueur;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('joueur1')
            ->add('joueur2')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Equipe::class,
        ));
    }
}
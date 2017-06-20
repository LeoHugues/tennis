<?php

namespace OrganisationBundle\Form\Gestion_joueur;


use OrganisationBundle\Repository\PaysRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use OrganisationBundle\Entity\Pays;
class CreerJoueurFormType extends AbstractType
{

    public function __construct()
    {
    }

    public function getName()
    {
        return 'créer-joueur-form';
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('prenom', TextType::class, array('label' => 'Prénom'))
            ->add('naissance', DateType::class, array('label' => 'Date de naissance', 'years' => range(1900,2017)))
            ->add('classement', IntegerType::class, array('label' => 'classement'))
            ->add('nationalite', EntityType::class, array(
                                                            'class' => 'OrganisationBundle:Pays',
                                                            'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OrganisationBundle\Entity\Joueur',
        ));
    }
}

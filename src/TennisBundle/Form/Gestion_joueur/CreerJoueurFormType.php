<?php

namespace TennisBundle\Form\Gestion_joueur;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
            ->add('prenom', TextType::class, array('label' => 'Prénom'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TennisBundle\Entity\Joueur',
        ));
    }
}

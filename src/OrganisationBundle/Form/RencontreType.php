<?php

namespace OrganisationBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OrganisationBundle\Form\EquipeType;
use OrganisationBundle\Entity\Matchs;

class RencontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipes1', EquipeType::class)
            ->add('equipes2', EquipeType::class)
            ->add('terrain')
            ->add('arbitre', EntityType::class, array(
                'class' => 'UserBundle\Entity\User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere("u.roles like '%ROLE_ARBITRE%' ")
                        ->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'username',
            ))
            ->add('date')
            ->add('nbSets', ChoiceType::class, array(
                'choices'  => array(
                    '5 sets' => 5,
                    '3 sets' => 3,
                ),
            ))
            ->add('nvxCompet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Matchs::class,
        ));
    }

}
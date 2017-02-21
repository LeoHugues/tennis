<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2/21/17
 * Time: 11:51 AM
 */

namespace TennisBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TennisBundle\Entity\Terrain;

class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Terrain::class,
        ));
    }
}
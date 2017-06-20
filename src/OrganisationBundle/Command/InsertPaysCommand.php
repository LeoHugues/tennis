<?php
/**
 * Created by PhpStorm.
 * User: julien_mathe
 * Date: 07/06/2017
 * Time: 14:49
 */
namespace OrganisationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OrganisationBundle\Entity\Pays;

class InsertPaysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('insertPays')
            ->setDescription('Insertion des pays en bdd');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = 'web/component/css/flags/4x3/*.svg';
        $files = glob($dir,GLOB_BRACE);
        $output->writeln('test');
        foreach($files as $image)
        {
            $pays = new Pays(substr($image,28,3),substr($image,4));
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist($pays);
        }
        $em->flush();
    }
}
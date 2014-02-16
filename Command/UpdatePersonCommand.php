<?php
namespace Cerad\Bundle\PersonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Cerad\Component\Excel\Excel;

class UpdatePersonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('cerad:person:update')
            ->setDescription('Update person');
          //->addArgument   ('importFile', InputArgument::REQUIRED, 'Import File')
          //->addArgument   ('truncate',   InputArgument::OPTIONAL, 'Truncate')
        ;
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $personRepo = $this->getService('cerad_person.person_repository');
        $persons = $personRepo->findAll();
        foreach($persons as $person)
        {
            if (!$person->getGuid())
            {
                $person->setGuid($this->genGuid());
            }
        }
        $personRepo->commit();
    }
    protected function genGuid() 
    { 
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', 
            mt_rand(0,     65535), mt_rand(0,     65535), mt_rand(0, 65535), 
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), 
            mt_rand(0,     65535), mt_rand(0,     65535));  
    }
}
?>

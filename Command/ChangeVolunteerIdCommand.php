<?php
namespace Cerad\Bundle\PersonBundle\Command;

/* =========================================
 * 21 Jan 2014
 * No longer needed after person_fed schema changes
 */
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeVolunteerIdCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('cerad:person:change_volunteer_id')
            ->setDescription('Update person')
            ->addArgument   ('oldFedId', InputArgument::REQUIRED, 'Old ID')
            ->addArgument   ('newFedId', InputArgument::REQUIRED, 'New ID')
        ;
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return;
        
        $personRepo = $this->getService('cerad_person.person_repository');
        
        $oldFedId = $input->getArgument('oldFedId'); // 'AYSOV66723556';
        $newFedId = $input->getArgument('newFedId'); // 'AYSOV99427977';
        
        $personFed = $personRepo->findFed($oldFedId);
        
        if (!$personFed) return;
        
        $personRepo->changeFedId($personFed,$newFedId);
        
        return;        
    }
 }
?>

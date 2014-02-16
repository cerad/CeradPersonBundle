<?php
namespace Cerad\Bundle\PersonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePersonPlanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('cerad_person:update:person_plan_name')
            ->setDescription('Update person plan name');
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
            $plans = $person->getPlans();
            foreach($plans as $plan)
            {
                if (!$plan->getPersonName()) 
                {
                    $plan->setPersonName($person->getName()->full);
                    $plan->setUpdatedOn();
                }
            }
        }
        $personRepo->commit();
    }
}
?>

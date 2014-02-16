<?php
namespace Cerad\Bundle\PersonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeletePersonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('cerad:person:delete')
            ->setDescription('Delete person')
            ->addArgument   ('personId', InputArgument::REQUIRED, 'Person ID')
        ;
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $personRepo = $this->getService('cerad_person.person_repository');
        
        $personId = $input->getArgument('personId');
        
        $person = $personRepo->find($personId);
        
        if (!$person) return;
        
        $personGuid = $person->getGuid();
        
        $personRepo->delete($person);
        $personRepo->commit();
        
        if ($personGuid)
        {
            $userRepo = $this->getService('cerad_user.user_repository');
            $user = $userRepo->findOneByPersonGuid($personGuid);
            if ($user)
            {
                $userRepo->delete($user);
                $userRepo->commit();
            }
        }
        return;        
    }
 }
?>

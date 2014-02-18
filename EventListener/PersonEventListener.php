<?php
namespace Cerad\Bundle\PersonBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Cerad\Bundle\CoreBundle\Events\PersonEvents;

use Cerad\Bundle\CoreBundle\Event\Person\FindByEvent;
use Cerad\Bundle\CoreBundle\Event\Person\FindPlanByProjectAndPersonEvent;

class PersonEventListener extends ContainerAware implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array
        (
            PersonEvents::FindPerson              => array('onFindPerson'        ),
            PersonEvents::FindPersonById          => array('onFindPersonById'    ),
            PersonEvents::FindPersonByGuid        => array('onFindPersonByGuid'  ),
            PersonEvents::FindPersonByFedKey      => array('onFindPersonByFedKey'),
            
            
            PersonEvents::FindOfficialsByProject  => array('onFindOfficialsByProject'),
            
            PersonEvents::FindPlanByProjectAndPerson     => array('onFindPlanByProjectAndPerson'),   
            PersonEvents::FindPlanByProjectAndPersonName => array('onFindPlanByProjectAndPerson'),   
        );
    }
    protected $personRepositoryServiceId;
    
    public function __construct($personRepositoryServiceId)
    {
        $this->personRepositoryServiceId = $personRepositoryServiceId;
    }
    protected function getPersonRepository()
    {
        return $this->container->get($this->personRepositoryServiceId);
    }
    public function onFindOfficialsByProject(Event $event)
    {
        // Just means a listener was available
        $event->stopPropagation();
        
        $projectKey = $event->project->getKey();
        
        $event->officials = $this->getPersonRepository()->findOfficialsByProject($projectKey);        
    }
    public function onFindPlanByProjectAndPerson(FindPlanByProjectAndPersonEvent $event)
    {
        $plan = $this->getPersonRepository()->findPlanByProjectAndPerson($event->getProject(),$event->getPerson());
        if ($plan) 
        {
            $event->stePlan($plan);
            $event->stopPropagation();
        }
    }
    
    public function onFindPersonByProjectName(Event $event)
    {
        // Just means a listener was available
        $event->stopPropagation();
        
        // Lookup
        $event->person = $this->getPersonRepository()->findOneByByProjectName($event->projectKey,$event->personName);
        
        return;
    }
    public function onFindPersonById(FindByEvent $event)
    {
        $person = $this->getPersonRepository()->find($event->getParam());
        if ($person)
        {
             $event->setPerson($person);
             $event->stopPropagation();
        }
    }
    public function onFindPersonByGuid(FindBy $event)
    {
        $person = $this->getPersonRepository()->findByGuid($event->getParam());
        if ($person)
        {
             $event->setPerson($person);
             $event->stopPropagation();
        }
    }
    public function onFindPersonByFedKey(Event $event)
    {
        // Just means a listener was available
        $event->stopPropagation();
        
        // Extract
        $fedKey = $event->getParam();
        if (!$fedKey) return;
        
        // Lookup
        $personRepo = $this->getPersonRepository();
        $person = $personRepo->findByFedKey($fedKey);
        if ($person)
        {
             $event->setPerson($person);
             $event->stopPropagation();
             return;
        }
        // Try different prefixes
        foreach(array('AYSOV','USSFC','NFHSC') as $prefix)
        {
            $person = $personRepo->findOneByFedKey($prefix . $fedKey);
            if ($person)
            {
                $event->setPerson($person);
                $event->stopPropagation();
                return;
            }
        }
        return;
    }
}
?>

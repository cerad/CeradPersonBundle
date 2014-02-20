<?php
namespace Cerad\Bundle\PersonBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Cerad\Bundle\CoreBundle\Events\PersonEvents;

use Cerad\Bundle\CoreBundle\Event\FindPersonEvent;
use Cerad\Bundle\CoreBundle\Event\RegisterProjectPersonEvent;

use Cerad\Bundle\CoreBundle\Event\Person\FindPlanByProjectAndPersonEvent;

class PersonEventListener extends ContainerAware implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array
        (
            PersonEvents::FindPerson              => array('onFindPerson'        ),
          //PersonEvents::FindPersonById          => array('onFindPersonById'    ),
          //PersonEvents::FindPersonByGuid        => array('onFindPersonByGuid'  ),
          //PersonEvents::FindPersonByFedKey      => array('onFindPersonByFedKey'),
            
            
            PersonEvents::FindOfficialsByProject  => array('onFindOfficialsByProject'),
            
            PersonEvents::FindPlanByProjectAndPerson     => array('onFindPlanByProjectAndPerson'),   
            PersonEvents::FindPlanByProjectAndPersonName => array('onFindPlanByProjectAndPerson'),
            
            PersonEvents::RegisterProjectPerson => array('doRegisterProjectPerson'),
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
    /* ==============================================================
     * Individual finders
     */
    public function onFindPerson(FindPersonEvent $event)
    {
        $person = $this->getPersonRepository()->findPerson($event->getSearch());
        if ($person)
        {
             $event->setPerson($person);
             $event->stopPropagation();
        }
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
    public function doRegisterProjectPerson(RegisterProjectPersonEvent $event)
    {
        // Unpack
        $project    = $event->getProject();
        $person     = $event->getPerson();
        $personFed  = $event->getPersonFed();
        $personPlan = $event->getPersonPlan();
        
        $assignor    = $project->getAssignor();
        $certReferee = $personFed->getCertReferee();
        
        $tplData = array();
        $tplData['project']     = $project;
        $tplData['assignor']    = $assignor;
        $tplData['person']      = $person;
        $tplData['personFed']   = $personFed;
        $tplData['personPlan']  = $personPlan;
        $tplData['certReferee'] = $certReferee;
        
        $templating = $this->container->get('templating');
        
        // Pull from project maybe? Use event->by?
        $tplEmailSubject = '@CeradApp/ProjectPerson/Register/RegisterEmailSubject.html.twig';
        $tplEmailContent = '@CeradApp/ProjectPerson/Register/RegisterEmailContent.html.twig';
        
        $subject = $templating->render($tplEmailSubject,$tplData);
        $content = $templating->render($tplEmailContent,$tplData);
        
      //echo $subject . '<br />';
      //echo nl2br($content);
      //die();
      
        // Admin stuff
        $fromName  = $assignor->getPrefix();
        $fromEmail = $this->container->getParameter('mailer_user'); // 'admin@zayso.org';
        
        // Referee stuff
        $personName  = $person->getName()->full;
        $personEmail = $person->getEmail();
        
        // Assignor stuff
        $assignorName  = $assignor->getName();
        $assignorEmail = $assignor->getEmail();
        
        // bcc stuff
        $adminName =  'Art Hundiak';
        $adminEmail = 'ahundiak@gmail.com';
        
        // This goes to the assignor
        $assignorMessage = \Swift_Message::newInstance();
        $assignorMessage->setSubject($subject);
        $assignorMessage->setBody   ($content);
        $assignorMessage->setFrom   (array($fromEmail     => $fromName));
        $assignorMessage->setBcc    (array($adminEmail    => $adminName));
        $assignorMessage->setTo     (array($assignorEmail => $assignorName));
        $assignorMessage->setReplyTo(array($personEmail   => $personName));
        
        // This goes to the referee
        $personMessage = \Swift_Message::newInstance();
        $personMessage->setSubject($subject);
        $personMessage->setBody   ($content);
        $personMessage->setFrom   (array($fromEmail     => $fromName));
        $personMessage->setTo     (array($personEmail   => $personName));
        $personMessage->setReplyTo(array($assignorEmail => $assignorName));

        // And send
        $mailer = $this->container->get('mailer');
        $mailer->send($personMessage);
        $mailer->send($assignorMessage);
    }
}
?>

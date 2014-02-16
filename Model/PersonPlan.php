<?php
namespace Cerad\Bundle\PersonBundle\Model;

use Cerad\Bundle\PersonBundle\Model\BaseModel;

use Cerad\Bundle\PersonBundle\Model\Person;

/* =======================================
 * Refactored to make the project key the actual project id
 * 
 * plan.plan
 */
class PersonPlan extends BaseModel
{
    protected $id;
    protected $person;
    protected $personName;
    
    protected $projectId;
    protected $status   = 'Active';
    protected $verified = 'No';
    
    protected $createdOn;
    protected $updatedOn;
    
    // These are basically value objects
    protected $basic = array();
    protected $avail;
    protected $level;
    protected $notes; // For now, notes are being stored under the basic array
   
    public function __construct($id = null, $planProps = array())
    {
    //    $this->id = $id;
    //    $this->setPlanProperties($planProps);
        $this->createdOn = new \DateTime();
    }
    public function getId()         { return $this->id;        }
    public function getBasic()      { return $this->basic;     }
    public function getNotesx()     { return $this->notes;     }

    public function getStatus()     { return $this->status;    }
    public function getVerified()   { return $this->verified;  }
    public function getProjectId()  { return $this->projectId; }
    public function getProjectKey() { return $this->projectId; }
    
    public function getPerson()     { return $this->person;    }
    public function getPersonName() { return $this->personName;}
   
    public function getCreatedOn () { return $this->createdOn; }
    public function getUpdatedOn()  { return $this->updatedOn; }
    
    public function setBasic    ($value) { $this->onPropertySet('basic',     $value); }
    public function setNotesx   ($value) { $this->onPropertySet('notes',     $value); }
    public function setStatus   ($value) { $this->onPropertySet('status',    $value); }
    public function setVerified ($value) { $this->onPropertySet('verified',  $value); }
    public function setProjectId($value) { $this->onPropertySet('projectId', $value); }
    
    public function setPerson(Person $person)  { $this->onPropertySet('person',    $person); }
    public function setPersonName($personName) { $this->onPropertySet('personName',$personName); }
    
    public function setCreatedOn(\DateTime $dt) { $this->onPropertySet('createdOn',$dt);  }
    
    public function setUpdatedOn($dt = null) 
    { 
        if (!$dt) $dt = new \DateTime();
        $this->onPropertySet('updatedOn',$dt);  
    }
    
    // Initializ from project->basic
    public function mergeBasicProps($props)
    {
        $propx = array();
        foreach($props as $name => $prop)
        {
            $default = array_key_exists('default',$prop) ? $prop['default'] : null;
            $propx[$name] = $default;
        }
        $this->basic = array_merge($propx,$this->basic);
        
    }
    /* ============================================================
     * Need some commanility and consistency
     */
    const NOTES = 'notes';
    
    const WILL_ATTEND  = 'attending';
    const WILL_REFEREE = 'refereeing';
    
    const WILL_MENTOR  = 'willMentor';
    const WANT_MENTOR  = 'wantMentor';
    
    const SHIRT_SIZE  = 'tshirt';

    // Hack this in for now
    const WILL_ATTEND_LEAGUE  = 'attendingLeague';
    const WILL_ATTEND_ASExtra = 'attendingASExtra';
    
    protected function getBasicValue($key)
    {
        return isset($this->basic[$key]) ? $this->basic[$key] : null;
    }
    public function getWillAttendLeague()  { return $this->getBasicValue(self::WILL_ATTEND_LEAGUE);  }
    public function getWillAttendASExtra() { return $this->getBasicValue(self::WILL_ATTEND_ASExtra); }
    
    public function getNotes () { return $this->getBasicValue(self::NOTES);  }
    
    public function getWillAttend () { return $this->getBasicValue(self::WILL_ATTEND);  }
    public function getWillReferee() { return $this->getBasicValue(self::WILL_REFEREE); }
    public function getWillMentor () { return $this->getBasicValue(self::WILL_MENTOR);  }
    public function getWantMentor () { return $this->getBasicValue(self::WANT_MENTOR);  }
    public function getShirtSize  () { return $this->getBasicValue(self::SHIRT_SIZE);   }
    
    public function setWillAttendLeague ($value) { return $this->setBasicParam(self::WILL_ATTEND_LEAGUE, $value); }
    public function setWillAttendASExtra($value) { return $this->setBasicParam(self::WILL_ATTEND_ASExtra,$value); }
    
    public function setNotes($value) { return $this->setBasicParam(self::NOTES, $value); }
    
    public function setWillAttend ($value) { return $this->setBasicParam(self::WILL_ATTEND, $value); }
    public function setWillReferee($value) { return $this->setBasicParam(self::WILL_REFEREE,$value); }
    public function setWillMentor ($value) { return $this->setBasicParam(self::WILL_MENTOR, $value); }
    public function setWantMentor ($value) { return $this->setBasicParam(self::WANT_MENTOR, $value); }
    public function setShirtSize  ($value) { return $this->setBasicParam(self::SHIRT_SIZE,  $value); }
    
    protected function setBasicParam($name,$value)
    {
        if (!isset($this->basic[$name])) return;
        
        if ($value == $this->basic[$name]) return;
        
        // Had problems making this cleaner
        $basic = $this->getBasic();
        
        $basic[$name] = $value;
       
        $this->setBasic($basic);
    }
    /* ====================================================
     * This sort of fits here
     * Want to be be able to customize by injecting something?
     */
    public function isOfficial()
    {
        $basic = $this->getBasic();
        
        $willAttend  = isset($basic['attending' ]) ? $basic['attending']  : null;
        switch(strtolower($willAttend))
        {
            case 'yes':
            case 'we1';
            case 'we2';
            case 'we12':
                break;
            default:
                return false;
        }
        $willReferee = isset($basic['refereeing']) ? $basic['refereeing'] : null;
        if (strtolower($willReferee) != 'yes') return false;
        
        return true;
    }        
}
?>

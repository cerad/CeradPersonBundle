<?php
namespace Cerad\Bundle\PersonBundle\Model;

use Cerad\Bundle\PersonBundle\Model\PersonFed;

class PersonFedCert extends BaseModel
{       
    const RoleReferee    = 'Referee';  // SoccerReferee? or add Sport?
    const RoleSafeHaven  = 'SafeHaven';
    
    const RoleAssignor   = 'Assignor';
    const RoleAssessor   = 'Assessor';
    
    const RoleCoachInstructor   = 'CoachInstructor';
    const RoleRefereeInstructor = 'RefereeInstructor';
    
    protected $id;
    protected $personFed;
    
    protected $role;     // Referee, Assessor etc
    protected $roleDate; // First certified as Referee
    
    protected $badge;      // As set by administrator or import
    protected $badgeUser;  // As set by user
    protected $badgeDate;
    protected $badgeVerified;
    
    protected $upgrading;
    protected $memYear;
    protected $orgKey;
    
    protected $status   = 'Active';
    protected $sort;     // Maybe later
    
    /* =================================================================
     * TODO: Accessors
     */
    public function getId       () { return $this->id;       }
    public function getPersonFed() { return $this->personFed;}
    public function getRole     () { return $this->role;     }
    public function getRoleDate () { return $this->roleDate; }
    
    public function getBadge        () { return $this->badge;         }
    public function getBadgeUser    () { return $this->badgeUser;     }
    public function getBadgeDate    () { return $this->badgeDate;     }
    public function getBadgeVerified() { return $this->badgeVerified; }

    public function getUpgrading() { return $this->upgrading; }
    public function getOrgKey   () { return $this->orgKey;    }
    public function getMemYear  () { return $this->memYear;   }
    public function getStatus   () { return $this->status;    }
    
    public function setRole    ($value) { $this->onPropertySet('role',    $value); }
    public function setRoleDate($value) { $this->onPropertySet('roleDate',$value); }
    
    public function setBadge        ($value) { $this->onPropertySet('badge',        $value); }
    public function setBadgeDate    ($value) { $this->onPropertySet('badgeDate',    $value); }
    public function setBadgeVerified($value) { $this->onPropertySet('badgeVerified',$value); }
    
    public function setStatus   ($value) { $this->onPropertySet('status',   $value); }
    public function setOrgKey   ($value) { $this->onPropertySet('orgKey',   $value); }
    public function setMemYear  ($value) { $this->onPropertySet('memYear',  $value); }
    public function setUpgrading($value) { $this->onPropertySet('upgrading',$value); }
   
    public function setPersonFed(PersonFed $personFed) { $this->onPropertySet('personFed',$personFed);   }
    
    public function __construct() {}

    public function setBadgeUser($badge) 
    { 
        $this->onPropertySet('badgeUser',$badge); 
    
        if (!$this->badge) $this->onPropertySet('badge',$badge); 
    }
    // Calc based on cert date
    public function getExperience($asOf = null)
    {
        if (!$this->roleDate) return null;
        
        if (!$asOf) $asOf = new \DateTime();
            
        $interval = $asOf->diff($this->roleDate);
        
        $years = $interval->format('%y');
        
        return $years;
    }
}
?>

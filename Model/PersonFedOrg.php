<?php
namespace Cerad\Bundle\PersonBundle\Model;

use Cerad\Bundle\PersonBundle\Model\PersonFed;

/* ==============================================
 * 14 Jan 2014 - No longer used by ayso/ussf apps
 */
class PersonFedOrg extends BaseModel
{
    const RoleState   = 'State';
    const RoleRegion  = 'Region';
    const RoleDefault = 'Default';
    
    protected $id;
    protected $fed;        // PersonFed
    protected $role;       // Primary, Region, State
    protected $roleDate;
    
    protected $orgKey;         // AYSOR0894, id only no relation
    protected $orgKeyVerified;
    
    protected $memYear;    // MY2012 etc
    
    protected $status   = 'Active';
    
    /* =================================================================
     * TODO: Accessors
     */
    public function getId        () { return $this->id;         }
    public function getFed       () { return $this->fed;        }
    public function getRole      () { return $this->role;       }
    public function getOrgId     () { return $this->orgId;      }
    public function getStatus    () { return $this->status;     }
    public function getVerified  () { return $this->verified;   }
    
    public function getMemYear   () { return $this->memYear;    }
    public function getMemLast   () { return $this->memLast;    }
    public function getMemFirst  () { return $this->memFirst;   }
    public function getMemExpires() { return $this->memExpires; }
    
    public function getBcYear    () { return $this->bcYear;     }
    public function getBcLast    () { return $this->bcLast;     }
    public function getBcFirst   () { return $this->bcFirst;    }
    public function getBcExpires () { return $this->bcExpires;  }
             
    public function setRole      ($value) { $this->onPropertySet('role',      $value); }
    public function setOrgId     ($value) { $this->onPropertySet('orgId',     $value); }
    public function setStatus    ($value) { $this->onPropertySet('status',    $value); }
    public function setVerified  ($value) { $this->onPropertySet('verified',  $value); }  
    
    public function setFed(PersonFed $fed) { $this->onPropertySet('fed',      $fed);   }
    
    public function setMemYear   ($value) { $this->onPropertySet('memYear',   $value); }
    public function setMemLast   ($value) { $this->onPropertySet('memLast',   $value); }
    public function setMemFirst  ($value) { $this->onPropertySet('memFirst',  $value); }
    public function setMemExpires($value) { $this->onPropertySet('memExpires',$value); }
    
    public function setBcYear    ($value) { $this->onPropertySet('bcYear',    $value); }
    public function setBcLast    ($value) { $this->onPropertySet('bcLast',    $value); }
    public function setBcFirst   ($value) { $this->onPropertySet('bcFirst',   $value); }
    public function setBcExpires ($value) { $this->onPropertySet('bcExpires', $value); }
    
    public function __construct() {}

}
?>

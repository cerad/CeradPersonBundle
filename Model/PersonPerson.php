<?php
namespace Cerad\Bundle\PersonBundle\Model;

use Cerad\Bundle\PersonBundle\Model\Person;

/* =================================================
 * One person (the master) can have some controll over their slaves
 * For example, the master can sign slaves up for games so they slaves will not need an account
 * The master should also be notified when changes will impact their slaves
 */
class PersonPerson extends BaseModel
{
    protected $id;
    
    protected $parent;
    protected $child;
    
    const RolePrimary = 'Primary'; // Each person relates to himself?
    const RoleFamily  = 'Family';  // John Sloan and his 3 (4?) family referees
    const RolePeer    = 'Peer';    // Referee teams formed for tournaments
    
    protected $role     = self::RolePrimary;
    protected $status   = 'Active';
    protected $verified = 'No';
    
    protected $project;  // NULL for families, non-null for tournament specific groupings
    
    public function getId      () { return $this->id;       }
    public function getRole    () { return $this->role;     }
    public function getParent  () { return $this->parent;   } // Person
    public function getChild   () { return $this->child;    } // Person
    public function getStatus  () { return $this->status;   }
    public function getVerified() { return $this->verified; }
    
    public function setRole    ($value) { $this->onPropertySet('role',    $value); }
    public function setStatus  ($value) { $this->onPropertySet('status',  $value); }
    public function setVerified($value) { $this->onPropertySet('verified',$value); }
    
    public function setParent  (Person $parent) { $this->onPropertySet('parent',  $parent); }
    public function setChild   (Person $child ) { $this->onPropertySet('child',   $child);  }
    
    public function __construct() {}
    
    public function isRolePrimary()
    {
        return $this->role == self::RolePrimary ? true : false;
    }
    public function isRoleFamily()
    {
        return $this->role == self::RoleFamily ? true : false;
    }
    public function isRolePeer()
    {
        return $this->role == self::RolePeer ? true : false;
    }
    public function setRolePrimary()
    {
        $this->setRole(self::RolePrimary);
    }
    public function setRoleFamily()
    {
        $this->setRole(self::RoleFamily);
    }
    public function setRolePeer()
    {
        $this->setRole(self::RolePeer);
    }
}
?>

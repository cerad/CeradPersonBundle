<?php
namespace Cerad\Bundle\PersonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Cerad\Bundle\PersonBundle\Model\PersonFed as PersonFedModel;;

class PersonFed extends PersonFedModel
{   
    public function __construct()
    {
        parent::__construct();
        
        $this->orgs  = new ArrayCollection();
        $this->certs = new ArrayCollection(); 
    }
    public function createOrg ($params = null) { return new PersonFedOrg ($params); }
    public function createCert($params = null) { return new PersonFedCert($params); }
}
?>

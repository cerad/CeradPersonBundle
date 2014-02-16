<?php
namespace Cerad\Bundle\PersonBundle\Model;

class PersonAddress extends BaseValueObject
{
    public $street1;
    public $street2;
    public $city;
    public $state;
    public $country;
    public $zipcode;
   
    public function __construct(
        $street1 = null,
        $street2 = null,
        $city    = null,
        $state   = null,
        $country = null,
        $zipcode = null
    )
    {
        $this->propNames = array('street1','street2','city','state','country','zipcode');
        
        if ($this->hydrate($street1)) return;
        
        foreach($this->propNames as $propName) { $this->$propName = $$propName; } 
    }
}
?>

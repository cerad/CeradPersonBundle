<?php
namespace Cerad\Bundle\PersonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Cerad\Bundle\PersonBundle\Model\Person        as PersonModel;
use Cerad\Bundle\PersonBundle\Model\PersonName    as PersonNameModel;
use Cerad\Bundle\PersonBundle\Model\PersonAddress as PersonAddressModel;

use Cerad\Bundle\PersonBundle\Entity\PersonFed;
use Cerad\Bundle\PersonBundle\Entity\PersonPlan;
use Cerad\Bundle\PersonBundle\Entity\PersonPerson;

class Person extends PersonModel
{   
    protected $id;
    
    /* =========================================
     * Value objects
     */
    private $nameFull;
    private $nameFirst;
    private $nameLast;
    private $nameNick;
    private $nameMiddle;
    
    private $addressCity;
    private $addressState;
    private $addressZipcode;
    
    public function getId() { return $this->id; }
    
    public function __construct()
    {
        parent::__construct();

        $this->feds    = new ArrayCollection(); 
        $this->plans   = new ArrayCollection();
        $this->persons = new ArrayCollection(); 
    }
    public function createFed ($params = null) { return new PersonFed ($params); }
    public function createPlan($params = null) { return new PersonPlan($params); }
    
    public function createPersonPerson($params = null) { return new PersonPerson($params); }
    
    /* ===============================================
     * Was hoping to do this with events but this is acutally cleaner and works
     */
    public function setName(PersonNameModel $name) 
    { 
        parent::setName($name);
        
        $this->dehydrate('name',$this->name);
    }
    public function setAddress(PersonAddressModel $address) 
    { 
        parent::setAddress($address);
        
        $this->dehydrate('address',$this->address);
    }
    
    /* ======================================================
     * Value objects hydration stuff
     * Currently assume actual objects
     * Might be able to handle arrays as well
     * Instead of a prop list might be nice to implement array_keys
     * 
     * Use this to trigger updates
     */
    protected function dehydrate($prefix,$item)
    {
        foreach($item->propNames as $propName)
        {
            $propNameThis = $prefix . ucfirst($propName);
            
            if (property_exists($this,$propNameThis))
            {
                $propValueOld = $this->$propNameThis;
                $propValueNew = $item->$propName;
                
                if ($propValueOld != $propValueNew)
                {
                    $this->onPropertyChanged($propNameThis,$propValueOld,$propValueNew);
                }
                
                $this->$propNameThis = $propValueNew;
            }
        }
    }
    protected function hydrate($prefix,$item)
    {
        foreach($item->propNames as $propName)
        {
            $propNameThis = $prefix . ucfirst($propName);
            
            $item->$propName = property_exists($this,$propNameThis) ? $this->$propNameThis : null;
         }
    }
    /* ================================================
     * For D2.4 supposed to get an event args which would allow
     * Recomputing change sets and altering the data
     */
    public function onPreUpdate()
    {
        return; // No longer needed I hope
        
        print_r($args->getEntityChangeSet); die();
        
        // VOs
        $this->dehydrate('name',   $this->name);
        $this->dehydrate('address',$this->address);
    }
    public function onPrePersist()
    {
        return; // No longer needed I hope
        // VOs
        $this->dehydrate('name',   $this->name);
        $this->dehydrate('address',$this->address);
    }
    public function onPostLoad()
    {
        $this->name = $this->createName();
        $this->hydrate('name',$this->name);
        
        $this->address = $this->createAddress();
        $this->hydrate('address',$this->address);
    }
}
?>

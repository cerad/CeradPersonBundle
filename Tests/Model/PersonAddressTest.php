<?php
namespace Cerad\Bundle\PersonBundle\Tests\Model;

use Cerad\Bundle\PersonBundle\Model\Person;
use Cerad\Bundle\PersonBundle\Model\PersonAddress;

class PersonAddressTest extends \PHPUnit_Framework_TestCase
{
    public function testPersonAddress()
    {
        $person = new Person();
        
        // Getting back a value object created with the constructor
        $address1 = $person->getAddress();
        $this->assertTrue($address1 instanceOf PersonAddress);
        
        $address2 = new PersonAddress(null,null,'Huntsville','AL');
        $person->setAddress($address2);
        
        // New PersonName object was created because values changed
        $address3 = $person->getAddress();
        $this->assertFalse($address2 === $address3);
        
        $this->assertEquals('Huntsville',$address3->city);
        $this->assertEquals('AL',        $address3->state);
        
    }
}

<?php

namespace Cerad\Bundle\PersonBundle\Tests\Model;

use Cerad\Bundle\PersonBundle\Model\Person;

use Cerad\Bundle\PersonBundle\Model\PersonFed;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $this->assertTrue($person instanceOf Person);
                
      //$id = $person->getId();
      //$this->assertInternalType('string',$id);
        
      //$this->assertEquals(36,strlen($id));
        
        $this->assertInternalType('array', $person->getFeds()); 
        
    }
    /* ============================================================
     * Really just a test to verify a method can be both static and dynamic
     * Static properties cannot be accessed through an instance
     * But it's okay for methods
     */
    public function testGenders()
    {
        $genders1 = Person::getGenderTypes();
        $this->assertEquals('Female',$genders1[Person::GenderFemale]);
        
        $person = new Person();
        $genders2 = $person->getGenderTypes();
        $this->assertEquals('Male',$genders2[Person::GenderMale]);
    }
    public function testPersonFed()
    {
        $person = new Person();
        $fed = $person->createFed();
        
        $this->assertTrue($fed instanceOf PersonFed);
        
    }
}

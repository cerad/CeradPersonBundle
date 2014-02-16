<?php
namespace Cerad\Bundle\PersonBundle\Tests\Model;

use Cerad\Bundle\PersonBundle\Model\Person;
use Cerad\Bundle\PersonBundle\Model\PersonName;

class PersonNameTest extends \PHPUnit_Framework_TestCase
{
    /* ==============================================
     * So PersonName is a value object
     * Getting and setting will always create anew object even if the values are identical
     * This prevents unwanted side effects caused when you change the
     * properties of a retrieved object
     */
    public function testPersonName()
    {
        $person = new Person();
        
        // Getting back a value object created with the constructor
        $name1 = $person->getName();
        $this->assertTrue($name1 instanceOf PersonName);
       
        $name2 = new PersonName('Art Hundiak','Arthur','Hundiak','Hondo');
        $person->setName($name2);
        
        // New PersonName object was created because values changed
        $name3 = $person->getName();
        $this->assertFalse($name1 === $name3);
        
        // This shows that even though the values are different, have different object
        $this->assertEquals ($name2,$name3);
        $this->assertNotSame($name2,$name3);
        
        $this->assertTrue ($name2 ==  $name3);
        $this->assertFalse($name2 === $name3);
        
        // Verify some values
        $this->assertEquals ('Arthur',$name3->first);
        $this->assertEquals ('Hondo', $name3->nick);
        
        /* =================================================
         * Normally you would not do this but forms will
         * So verify that the person's name object is not impacted
         */
        $name3->first = 'Art';
        $name4 = $person->getName();

        $this->assertNotEquals($name3,$name4);
        $this->assertNotSame  ($name3,$name4);
        
        $this->assertFalse($name3 ==  $name4);
        $this->assertFalse($name3 === $name4);       
    }
    public function testPersonNameCreate()
    {
        $person = new Person();
        $name1 = $person->createName(array('full' => 'Art Hundiak','nick' => 'Hondo'));
        $this->assertTrue($name1 instanceOf PersonName);
        
        $this->assertEquals ('Art Hundiak',$name1->full);
        $this->assertEquals ('Hondo',      $name1->nick);
        
    }
}

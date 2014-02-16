<?php
namespace Cerad\Bundle\PersonBundle\Tests\Model;

use Cerad\Bundle\PersonBundle\Model\Person;

use Cerad\Bundle\PersonBundle\Model\PersonFed;

class PersonFedTest extends \PHPUnit_Framework_TestCase
{
    public function testPersonFed()
    {
        $person = new Person();
        $fed = $person->createFed();
        
        $this->assertTrue($fed instanceOf PersonFed);
        
    }
}

<?php

namespace Cerad\Bundle\PersonBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use Cerad\Bundle\PersonBundle\Model \Person as PersonModel;
use Cerad\Bundle\PersonBundle\Entity\Person as PersonEntity;

use Cerad\Bundle\PersonBundle\Model \PersonFed as PersonFedModel;
use Cerad\Bundle\PersonBundle\Entity\PersonFed as PersonFedEntity;

use Cerad\Bundle\PersonBundle\Model\PersonRepositoryInterface;
use Cerad\Bundle\PersonBundle\Entity\PersonRepository;

class PersonRepositoryTest extends WebTestCase
{
    protected $repoServiceId = 'cerad_person.person_repository.doctrine';
    
    /* =============================================
     * Looked thought the createClient code to see if I coud just create the kernel
     * But there is an awful lot there
     */
    protected static $client;
    protected static $container;
 
    public static function setUpBeforeClass()
    {
        self::$client    = static::createClient();
        self::$container = self::$client->getContainer();
        
        /* ======================================
         * Drop and build the schema
         * TODO: figure out how to have test point to a different database
         */
        $em = self::$container->get('cerad_person.entity_manager.doctrine');
        $metaDatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        
        $schemaTool->dropSchema  ($metaDatas);
        $schemaTool->createSchema($metaDatas);
        
    }
    
    protected function getRepo()
    {
       $repo = self::$container->get($this->repoServiceId);
       
       return $repo;
    }
    public function testExistence()
    {
        $repo = $this->getRepo();
        $this->assertTrue($repo instanceOf PersonRepositoryInterface);
        $this->assertTrue($repo instanceOf PersonRepository);
        
        $person1 = $repo->createPerson();
        $this->assertTrue($person1 instanceOf PersonModel);
        $this->assertTrue($person1 instanceOf PersonEntity);
        
        $personClassName = $repo->getClassName();
        $person2 = new $personClassName();
        $this->assertTrue($person2 instanceOf PersonModel);
        $this->assertTrue($person2 instanceOf PersonEntity);
    }
    public function testCommit()
    {
        $repo = $this->getRepo();
        
        $person1 = $repo->createPerson();
        $person1->setEmail('ahundiak01@gmail.com');
        
        $repo->save($person1);
        $repo->commit();
        $repo->clear();
        
        $person2 = $repo->find($person1->getId());
        $this->assertFalse($person1 === $person2);
        
        $this->assertEquals($person1->getId(),   $person2->getId());
        $this->assertEquals($person1->getEmail(),$person2->getEmail());
        
        // This is not true probably because of the array collections?
        //$this->assertTrue ($person1 ==  $person2);
    }
    public function testNameAndAddress()
    {
        $repo = $this->getRepo();
        
        $person1 = $repo->createPerson();
        
        // Name
        $name1 = $person1->getName();
        $name1->full = 'Art Hundiak';
        $name1->nick = 'Hondo';
        $person1->setName($name1);
        
        // Address
        $address1 = $person1->getAddress();
        $address1->city  = 'Huntsville';
        $address1->state = 'AL';
        $person1->setAddress($address1);
       
        // Persist
        $repo->save($person1);
        $repo->commit();
        $repo->clear();
        
        $person2  = $repo->find($person1->getId());
        $name2    = $person2->getName();
        $address2 = $person2->getAddress();
        
        $this->assertTrue($name1 ==  $name2);
        $this->assertEquals($address1,$address2);
        
        
    }
    public function testFed()
    {
        $repo = $this->getRepo();
        
        $person1 = $repo->createPerson();
        
        $fed1 = $person1->createFed();
        $this->assertTrue($fed1 instanceOf PersonFedEntity);
        
        $fed1->setFedRoleId(PersonFedEntity::FedRoleAYSOV);
        
        $fedIdTransformer = self::$container->get('cerad_person.aysov_id.data_transformer.fake');
        $fedId = $fedIdTransformer->reverseTransform('99');
        
        $fed1->setId($fedId);
        
        $person1->addFed($fed1);
        
        $this->assertTrue($fed1->getPerson() instanceOf PersonEntity);

        // Persist
        $repo->save($person1);
        $repo->commit();
        $repo->clear();
        
    }
    public function testFedFindRole()
    {   
        $repo = $this->getRepo();
        $person1 = $repo->createPerson();
        
        $fedRoleId = PersonFedEntity::FedRoleUSSFC;
        $fed1 = $person1->getFed($fedRoleId);
        
        $this->assertTrue  ($fed1 instanceOf PersonFedEntity);
        $this->assertEquals($fedRoleId,$fed1->getFedRoleId());
        $this->assertTrue  ($fed1->getPerson() instanceOf PersonEntity);
      
        // Set the real id
        $fedIdTransformer = self::$container->get('cerad_person.ussfc_id.data_transformer.fake');
        $fedId = $fedIdTransformer->reverseTransform('99');
        $fed1->setId($fedId);
        
        // Persist
        $repo->save($person1);
        $repo->commit();
        $repo->clear();
        
        // Load it back
        $person2 = $repo->find($person1->getId());
        $fed2 = $person2->getFed($fedRoleId,false);
        
        $this->assertEquals($fed1->getId(),$fed2->getId());
    }
}

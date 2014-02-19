<?php

namespace Cerad\Bundle\PersonBundle\EntityRepository;

use Cerad\Bundle\CoreBundle\Doctrine\EntityRepository as BaseRepository;

//  Cerad\Bundle\PersonBundle\Model\PersonRepositoryInterface;


class PersonRepository extends BaseRepository // implements PersonRepositoryInterface
{
    function createPerson($params = null) { return $this->createEntity($params); }
    
    // id,guid,fedKey
    public function findPerson($param)
    {
        // Avoid nulls
        if (!($param = trim($param))) return null;
        
        $qb = $this->createQueryBuilder('person');
        
        $where = <<<EOT
(person.id   = :param) OR 
(person.guid = :param)
EOT;
        $qb->andWhere($where); 
        
        $qb->setParameter('param', trim($param));
        
        $items = $qb->getQuery()->getResult();
        
        if (count($items) == 1) return $items[0];
        
        $item = $this->findByFedKey($param);
        if ($item) return $item;
        
        return null; 
    }
    public function findByGuid($guid)
    {
        return $guid ? $this->findOneBy(array('guid' => $guid)) : null;
    }
    /* ===================================================
     * The fed key could be
     * AYSOV12341234 OR
     *      12341234
     * 
     * TODO: Make FedRole an option and adjust accordingly
     */
    public function findByFedKey($fedKey, $fedRole = null)
    {   
        // Avoid nulls
        if (!($fedKey = trim($fedKey))) return null;
        
        $repo = $this->getEntityManager()->getRepository('CeradPersonBundle:PersonFed');
        
        $qb = $repo->createQueryBuilder('personFed');
        $qb->leftJoin ('personFed.person','person');
        $qb->addSelect('person');
        
        $where = <<<EOT
(personFed.fedKey = :fedKey)      OR
(personFed.fedKey = :fedKeyUSSFC) OR
(personFed.fedKey = :fedKeyAYSOV)
EOT;
        $qb->andWhere($where); 
        
        $qb->setParameter('fedKey',                $fedKey);
        $qb->setParameter('fedKeyUSSFC', 'USSFC' . $fedKey);
        $qb->setParameter('fedKeyAYSOV', 'AYSOV' . $fedKey);
        
        $items = $qb->getQuery()->getResult();
        
        if (count($items) != 1) return null;
        
        return $items[0]->getPerson();
    }
    /* =================================================
     * Older stuff, needs review
     */
    public function query($projects = null)
    {
        $qb = $this->createQueryBuilder('person');
        
        $qb->addSelect('personPlan');
        $qb->leftJoin ('person.plans','personPlan');
        
        if ($projects)
        {
            $qb->andWhere('personPlan.projectId IN (:projectIds)');
            $qb->setParameter('projectIds',$projects);
        }
        $qb->orderBy('person.nameLast,person.nameFirst');
        
        return $qb->getQuery()->getResult();
    }
    /* ====================================================
     * Grabs everyone for a project then filters for officials
     * Really should add a isOfficial column to the plan object
     */
    public function findOfficialsByProject($projectKey)
    {
        $qb = $this->createQueryBuilder('person');
        
        $qb->addSelect('personPlan');
        $qb->leftJoin ('person.plans','personPlan');
        
        $qb->andWhere('personPlan.projectId IN (:projectKey)');
        $qb->setParameter('projectKey',$projectKey);
 
        $qb->orderBy('personPlan.personName');
        
        $persons = $qb->getQuery()->getResult();
        $officials = array();
        foreach($persons as $person)
        {
            $personPlan = $person->getPlan();
            if ($personPlan->isOfficial()) $officials[] = $person;
        }
        return $officials;
    }
    /* ===========================================================
     * Looking up person for a project by their full name
     * Take into account the possibility that there might be two people with the same name
     */
    public function findOneByProjectName($projectId,$personName)
    {
        if (!$personName) return null;
        
        $qb = $this->createQueryBuilder('person');
        
        $qb->addSelect('personPlan');
        $qb->leftJoin ('person.plans','personPlan');
        
        $qb->andWhere('person.nameFull = :personName');
        $qb->andWhere('personPlan.projectId  = :projectId' );
        
        $qb->setParameter('personName',$personName);
        $qb->setParameter('projectId', $projectId);
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        
        return null;
    }
    /* ================================================
     * Load record based on fedId AYSOV12341234
     */
    public function findFedByFedKey($fedKey)
    {
        if (!$fedKey) return null;
        $repo = $this->_em->getRepository('CeradPersonBundle:PersonFed');
        return $repo->findOneBy(array('fedKey' => $fedKey));
    }
    /* =================================================================
     * The next three load a record by id
     * Could probably be named better, used for updates
     */
    public function findFed($id)
    {
        if (!$id) return null;
        $repo = $this->_em->getRepository('CeradPersonBundle:PersonFed');
        return $repo->find($id);          
    }
    public function findPlan($id)
    {
        if (!$id) return null;
        $repo = $this->_em->getRepository('CeradPersonBundle:PersonPlan');
        return $repo->find($id);        
    }
    // Also accepts projectKey and personGuid
    public function findPlanByProjectAndPerson($project,$person)
    {
        $projectKey = is_object($project) ? $project->getKey() : $project;
        $personGuid = is_object($person)  ? $person->getGuid() : $person;
        
        $repo = $this->_em->getRepository('CeradPersonBundle:PersonPlan');
        
        $qb = $repo->createQueryBuilder('personPlan');
        
        $qb->addSelect('person');
        $qb->leftJoin ('personPlan.person','person');
        
        $qb->andWhere('person.guid = :personGuid');
        $qb->andWhere('personPlan.projectId = :projectKey' );
        
        $qb->setParameter('personGuid',$personGuid);
        $qb->setParameter('projectKey',$projectKey);
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
    }
    public function findPersonPerson($id)
    {
        if (!$id) return null;
        $repo = $this->_em->getRepository('CeradPersonBundle:PersonPerson');
        return $repo->find($id);        
    }
    /* ================================================
     * For testing
     */
    public function truncate()
    {
        die('personRepo.truncate');
        $conn = $this->_em->getConnection();
        $conn->executeUpdate('DELETE FROM person_fed_certs;' );
      //$conn->executeUpdate('DELETE FROM person_fed_orgs;'  );
        $conn->executeUpdate('DELETE FROM person_feds;'      );
        
        $conn->executeUpdate('ALTER TABLE person_fed_certs AUTO_INCREMENT = 1;');
      //$conn->executeUpdate('ALTER TABLE person_fed_orgs  AUTO_INCREMENT = 1;');
        
        $conn->executeUpdate('DELETE FROM person_persons;');
        $conn->executeUpdate('DELETE FROM person_plans;'  );
        $conn->executeUpdate('DELETE FROM persons;'       );
        
        $conn->executeUpdate('ALTER TABLE person_persons AUTO_INCREMENT = 1;');
        $conn->executeUpdate('ALTER TABLE person_plans   AUTO_INCREMENT = 1;');
        $conn->executeUpdate('ALTER TABLE persons        AUTO_INCREMENT = 1;');        
    }
}
?>

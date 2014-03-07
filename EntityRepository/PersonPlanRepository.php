<?php

namespace Cerad\Bundle\PersonBundle\EntityRepository;

use Cerad\Bundle\CoreBundle\Doctrine\EntityRepository as BaseRepository;

class PersonPlanRepository extends BaseRepository
{
    // Specifically restricted to one project
    public function findByProject($project)
    {   
        $qb = $this->createQueryBuilder('personPlan');
        
        $qb->addSelect('person');
        $qb->leftJoin ('personPlan.person','person');
        
        $qb->andWhere('personPlan.projectId IN (:projectKey)');
        $qb->setParameter('projectKey',$project->getKey());
        
        $qb->orderBy('person.nameLast,person.nameFirst');
        
        return $qb->getQuery()->getResult();
    }
}
?>

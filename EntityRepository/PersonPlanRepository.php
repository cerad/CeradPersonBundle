<?php

namespace Cerad\Bundle\PersonBundle\EntityRepository;

use Cerad\Bundle\CoreBundle\Doctrine\EntityRepository as BaseRepository;

class PersonPlanRepository extends BaseRepository
{
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
}
?>

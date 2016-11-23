<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RateRepository extends EntityRepository
{

    public function findAllByPeriod($dateStart, $dateEnd)
    {
        $query = $this->createQueryBuilder('r')
            ->where('r.createdAt >= :dateStart')
            ->andWhere('r.createdAt < :dateEnd')
            ->setParameter('dateStart',$dateStart->format('Y-m-d'))
            ->setParameter('dateEnd',$dateEnd)
            ->getQuery();

        return $query->getResult();
    }    
    
    public function findAllByValue($dateStart, $dateEnd)
    {
        $query = $this->createQueryBuilder('r')
            ->select('COUNT(r.rate) AS countRates, r.rate')// IDENTITY(r.user) as user_id
            ->where('r.createdAt > :dateStart')
            ->andWhere('r.createdAt < :dateEnd')
            ->groupBy('r.rate')
            
            ->setParameter('dateStart',$dateStart->format('Y-m-d'))
            ->setParameter('dateEnd',$dateEnd)
            ->getQuery();

        return $query->getResult();        
    }
    
    
    public function hasRatedToday(User $user)
    {
        $now        = new \DateTime('now');    
        $yesterday  = $now->sub(new \DateInterval('P1D'));
        $query      = $this->createQueryBuilder('r')
             ->where('r.user = :user_id')
             ->andWhere('r.createdAt >= :yesterday')
             ->setParameter('yesterday', $yesterday)
             ->setParameter('user_id', $user->getId())
             ->getQuery();

        return empty($query->getResult()) ? false : true; 
    }
}
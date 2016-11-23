<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function findAllByFeature()
    {
        $query = $this->createQueryBuilder('u')
                
            ->orderBy('u.feature', 'DESC')
            ->getQuery();
        
//        echo $query->getDQL(); exit;

        return $query->getResult();
    }   


    
}
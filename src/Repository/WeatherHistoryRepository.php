<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\WeatherHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherHistory>
 */
class WeatherHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherHistory::class);
    }

    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.location = :location')
           ->setParameter('location', $location);

        $query = $qb->getQuery();

        return $query->getResult();
    }
}

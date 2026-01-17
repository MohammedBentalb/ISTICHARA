<?php

namespace App\Repository;
use App\Models\Avocat;

class AvocatRepository extends BaseRepository{
    protected static $entityName = Avocat::class;
     private static array $allowedSpecialites = [
        'Droit penal',
        'civil',
        'famille',
        'affaires'
    ];

    public function search(?string $search = null, ?string $filter = null, ?int $limit = null, ?int $offset = null){
        if($filter && !in_array($filter, self::$allowedSpecialites)) return [];
        $query = $this->em->createQueryBuilder()->select("a, v")->from(static::$entityName, "a")->leftJoin("a.ville", "v");
        
        if($search) $query->andWhere('LOWER(a.name) LIKE LOWER(:search)')->setParameter('search', "%" . $search ."%");
        if ($filter) $query->andWhere('a.specialite = :filter')->setParameter('filter', $filter);
        if($limit) $query->setMaxResults($limit);
        if($offset) $query->setFirstResult($offset);
        
        return $query->getQuery()->getArrayResult();
    }

}
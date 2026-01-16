<?php

namespace App\Repository;
use App\Models\Huissier;

class HuissierRepository extends BaseRepository {
        protected static $entityName = Huissier::class;
        private static array $typesOfActes = [
                'Signification',
                'execution',
                'constats'
        ];

                
        public function search(?string $search = null, ?string $filter = null, ?int $limit = null, ?int $offset = null){
                if($filter && !in_array($filter, self::$typesOfActes)) return [];
                $query = $this->em->createQueryBuilder()->select("h, v")->from(static::$entityName, "h")->leftJoin("h.ville", "v");

                if($search) $query->andWhere('LOWER(a.name) LIKE LOWER(:search)')->setParameter('search', "%" . $search ."%");
                if ($filter) $query->andWhere('a.specialite = :filter')->setParameter('filter', $filter);
                if($limit) $query->setMaxResults($limit);
                if($offset) $query->setFirstResult($offset);

                return $query->getQuery()->getArrayResult();
        }
}
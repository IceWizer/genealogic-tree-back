<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function save(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByOwner(User $owner): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.owner = :owner')
            ->setParameter('owner', $owner->getId()->toBinary())
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByOwnerAndQuery(User $owner, string $query): array
    {
        $queries = explode(' ', $query);
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.owner = :owner')
            ->setParameter('owner', $owner->getId()->toBinary())
            ->orderBy('p.id', 'ASC');

        $i = 0;
        foreach ($queries as $singleQuery) {
            $param = 'query_' . $i;
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('p.name', ':' . $param),
                    $queryBuilder->expr()->like('p.firstNames', ':' . $param),
                    $queryBuilder->expr()->like('p.birthName', ':' . $param)
                )
            );
            $queryBuilder->setParameter($param, '%' . $singleQuery . '%');
            $i++;
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }


    public function findPossibleChildren(Person $parent): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $query = $this->createQueryBuilder('p')
            ->andWhere('p.owner = :owner')
            ->setParameter('owner', $parent->getOwner()->getId()->toBinary())
            ->andWhere('p.id != :id')
            ->setParameter('id', $parent->getId()->toBinary())
            ->andWhere(
                $expr->orX(
                    $expr->isNull('p.parent1'),
                    $expr->isNull('p.parent2')
                )
            )
            ->orderBy('p.birthDate', 'ASC');
        if ($parent->getBirthDate() !== null) {
            $query->andWhere(
                $expr->orX(
                    $expr->lt('p.birthDate', ':birthDate'),
                    $expr->isNull('p.birthDate')
                )
            )
                ->setParameter('birthDate', $parent->getBirthDate());
        }
        if ($parent->getChildren()->count() > 0) {
            $query->andWhere('p.id NOT IN (:children)')
                ->setParameter('children', $parent->getChildren()->map(fn(Person $person) => $person->getId()->toBinary()));
        }

        return $query->getQuery()
            ->getResult();
    }
}

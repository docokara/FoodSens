<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function add(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOne($val)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT 
        r.tags,
        r.steps,
        r.people,
        r.budget,
        r.difficulty,
        r.preptime
        FROM App\Entity\Recipe r'
        );
        return $query->getResult();
    }



    public function findAll()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT 
       r
        FROM App\Entity\Recipe r'
        );
        return $query->getArrayResult();
    }
    public function findAllWithParam($name)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT r FROM App\Entity\Recipe r WHERE r.name LIKE :rname"
        );
        $query->setParameter('rname', '%' . $name . '%');
        return $query->getResult();
    }
}

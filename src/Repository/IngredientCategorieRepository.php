<?php

namespace App\Repository;

use App\Entity\IngredientCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientCategorie>
 *
 * @method IngredientCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientCategorie[]    findAll()
 * @method IngredientCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientCategorie::class);
    }

    public function add(IngredientCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IngredientCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAll()
   {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT i.id,i.name FROM App\Entity\IngredientCategorie i');
        return $query->getResult();
   }
//    /**
//     * @return IngredientCategorie[] Returns an array of IngredientCategorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IngredientCategorie
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

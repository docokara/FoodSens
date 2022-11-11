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
        $query = $entityManager->createQuery('SELECT i FROM App\Entity\IngredientCategorie i');
        return $query->getArrayResult();
    }
    public function findOneById($id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT i FROM App\Entity\IngredientCategorie i WHERE i.id = :id");
        $query->setParameter('id', $id);

        return $query->getResult();
    }
}

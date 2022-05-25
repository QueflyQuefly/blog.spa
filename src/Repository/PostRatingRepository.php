<?php

namespace App\Repository;

use App\Entity\PostRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostRating[]    findAll()
 * @method PostRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostRating::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PostRating $entity, bool $flush = true): void
    {
        $this
            ->_em
            ->persist($entity);

        if ($flush) {
            $this
                ->_em
                ->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PostRating $entity, bool $flush = true): void
    {
        $this
            ->_em
            ->remove($entity);

        if ($flush) {
            $this
                ->_em
                ->flush();
        }
    }
}

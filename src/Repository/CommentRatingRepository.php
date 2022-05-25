<?php

namespace App\Repository;

use App\Entity\CommentRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentRating[]    findAll()
 * @method CommentRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentRating::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CommentRating $entity, bool $flush = true): void
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
    public function remove(CommentRating $entity, bool $flush = true): void
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

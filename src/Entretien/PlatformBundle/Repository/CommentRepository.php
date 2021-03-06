<?php

namespace Entretien\PlatformBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends EntityRepository
{
  public function getCommentWithAdvert($id)
  {
    $qb = $this->createQueryBuilder('a');

    // On fait une jointure avec l'entité Advert avec pour alias « adv »
    $qb
      ->innerJoin('a.advert', 'adv')
      ->addSelect('adv')
      ->orderBy('a.date', 'DESC')
    ;

    // Enfin, on retourne le résultat
    return $qb
      ->getQuery()
      ->getResult()
      ;
  }
}

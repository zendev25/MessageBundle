<?php

namespace ZEN\MessageBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * DiscussionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DiscussionRepository extends EntityRepository {

    public function getDiscussionsGroup($id_group, $id_cat_message = 0, $archived = 0, $id_author = 0, $admin = 0) {
        $qb = $this->createQueryBuilder('d')
                ->where('d.group = ' . $id_group);
        if ($id_cat_message > 0) {
            $qb->andWhere('d.catMessage = ' . $id_cat_message);
        }
        if ($archived) {
            $qb->andWhere('d.archived = 1');
        } else {
            $qb->andWhere('d.archived = 0');
        }
        if ($id_author) {
            if ($admin) {
                //Si l'auteur n'est pas le user connécté alors c'est l'admin
                $qb->andWhere('d.author != ' . $id_author);
            } else {
                $qb->andWhere('d.author = ' . $id_author);
            }
        }
        $qb->orderBy('d.dateUpdate', 'DESC');
        
        return $qb->getQuery()->getResult();
    }

    public function countNewDiscussions($id_group = 0/*, $id_user = 0*/) {
        $qb = $this->createQueryBuilder('d')
                ->select('COUNT(d)');
        if ($id_group) {
            $qb->where('d.group = ' . $id_group)
                    ->andWhere('d.isRead = 0');
                    // != admin
//                    ->andWhere('d.author = ' . $id_user);
        } else {
            $qb->where('d.isReadAdmin = 0');
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countDiscussions($id_group = 0) {
        $qb = $this->createQueryBuilder('d')
                ->select('COUNT(d)');
        if ($id_group) {
            $qb->where('d.group = ' . $id_group);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

}

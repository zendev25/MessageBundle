<?php

namespace ZEN\MessageBundle\Repository;

   
/**
 * CatMessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CatMessageRepository extends \ZEN\LocaleBundle\Entity\Translation\TranslatableRepository {

    public function getCatMessageByLangue($langue) {

        $qb = $this->createQueryBuilder('ct')
               /* ->leftJoin('ct.catMessageLang', 'ctl')
                ->addSelect('ctl')
                ->where('ctl.language = ' . $langue)*/;

        return $qb;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Koretskiy
 * Date: 15.12.13
 * Time: 15:58
 */

namespace GBP\BacardiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository {

	public function findBySexJoinedToCategory($isFemale)
	{
		$query = $this->getEntityManager()
			->createQuery('
            SELECT i, c FROM GBPBacardiBundle:Item i
            JOIN i.category c
            WHERE c.isFemale = :isFemale'
			)->setParameter('isFemale', (bool)$isFemale);
		try {
			return $query->getArrayResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
} 
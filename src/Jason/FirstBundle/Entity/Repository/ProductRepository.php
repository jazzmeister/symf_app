<?php

namespace Jason\FirstBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
	public function findAllOrderedByName()
	{
		return $this->getEntityManager()
		->createQuery(
			'SELECT p FROM JasonFirstBundle:Product p ORDER BY p.name ASC'
		)
		-getResult();
	}
}

<?php

namespace Website\PromotionsBundle\Repository;

use Doctrine\ORM\Query;


/**
 * PromotionRepository
 */
class PromotionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $id
     * @return array|null
     */
    public function getPromotionsWithProductsWithStatistics($id = null)
    {
        if (null != $id) {
            $subquery = " WHERE prm.userId = :id ";
        } else {
            $subquery = "";
        }

        $em = $this->getEntityManager();
        $query = $em->createQuery("
              SELECT prm
              FROM 'Website\PromotionsBundle\Entity\Promotion' AS prm 
              INNER JOIN 'Website\PromotionsBundle\Entity\PromotionProduct' AS pp WITH prm.id = pp.promotion
              LEFT JOIN 'Website\PromotionsBundle\Entity\Product' AS prd WITH prd.id = pp.product
              ".$subquery."
              GROUP BY prm.id, prd.productArticle, pp, prd.id
              ORDER BY prm.id DESC"
        );

        if (null != $id) {
            $query->setParameters(array('id' => $id));
        }

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\Query\QueryException $e) {
            return ['error' => 'Code ' . $e->getCode() . ' Message '. $e->getMessage() . ' Line ' . $e->getLine()];
        }
    }
}
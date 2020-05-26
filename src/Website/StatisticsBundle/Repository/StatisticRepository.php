<?php

namespace Website\StatisticsBundle\Repository;

use Doctrine\ORM\Query;


/**
 * StatisticRepository
 */
class StatisticRepository extends \Doctrine\ORM\EntityRepository
{
    /*
    **
    * @param $id
    * @param $startdate
    * @param $enddate
    */
    public function getStatisticsByIdAndDate($id, $startdate, $enddate = null)
    {
        if (null != $enddate) {
            $subquery = "AND st.createdAt <= :enddate";
        } else {
            $subquery = "";
        }

        $em = $this->getEntityManager();
        $query = $em->createQuery("
              SELECT st
              FROM 'Website\StatisticsBundle\Entity\Statistic' AS st 
              WHERE st.productArticle = :id AND st.createdAt >= :startdate ".$subquery."
              ORDER BY st.createdAt DESC"
        );

        if (null != $enddate) {
            $query->setParameters(array('id' => $id, 'startdate' => $startdate, 'enddate' => $enddate));
        } else {
            $query->setParameters(array('id' => $id, 'startdate' => $startdate));
        }

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\Query\QueryException $e) {
            return ['error' => 'Code ' . $e->getCode() . ' Message '. $e->getMessage() . ' Line ' . $e->getLine()];
        }
    }
}
<?php

namespace Website\StatisticsBundle\Repository;

use Symfony\Component\Validator\Constraints\DateTime;


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
    * @return array|null
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
            $query->setParameters(array('id' => $id, 'startdate' => $startdate, 'enddate' => $enddate ));
        } else {
            $query->setParameters(array('id' => $id, 'startdate' => $startdate ));
        }

        try {
            return $query->getScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
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
    * @return array|null
    */
    public function getStatisticsById($id, $startdate)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
              SELECT st
              FROM 'Website\StatisticsBundle\Entity\Statistic' AS st 
              WHERE st.createdAt >= :startdate AND st.productArticle = :id
              ORDER BY st.createdAt DESC"
        );

        $query->setParameters(array('id' => $id, 'startdate' => $startdate->format('Y-m-d') ));

        try {
            return $query->getScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /*
    **
    * @param $id
    * @param $startdate
    * @param $enddate
    * @return array|null
    */
    public function getStatisticsByIdAndDate($id, $startdate, $enddate)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("
              SELECT st
              FROM 'Website\StatisticsBundle\Entity\Statistic' AS st 
              WHERE (st.createdAt >= :startdate AND st.createdAt <= :enddate) AND st.productArticle = :id
              ORDER BY st.createdAt DESC"
        );

        $query->setParameters(array('id' => $id, 'startdate' => $startdate->format('Y-m-d'), 'enddate' => $enddate->format('Y-m-d') ));

        try {
            return $query->getScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
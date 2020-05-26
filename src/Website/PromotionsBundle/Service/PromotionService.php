<?php

namespace Website\PromotionsBundle\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Doctrine\ORM\EntityManagerInterface;


/**
 * Promotion Service
 *
 */
class PromotionService
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Get statistics in structure.
     */
    public function getStatisticsInDataStructure($promotions)
    {
        foreach ($promotions as $i => $promotion) {
            foreach ($promotion['promprod'] as $j => $promProd) {
                $statistics = $this->em->getRepository('WebsiteStatisticsBundle:Statistic')->getStatisticsByIdAndDate($promProd["productId"], $promotion["createdAt"], $promProd["prevDate"]);

                foreach ($statistics as $statistic) {
                    $promotions[$i]['promprod'][$j]['statistic'][] = [
                        "clicks" => $statistic->getClicks(),
                        "statisticCreatedAt" => $statistic->getCreatedAt()->format('Y-m-d H:i:s')
                    ];
                }
            }
        }
        return $promotions;
    }

    /**
     * Create datas structure.
     */
    public function getDataStructure($promotions)
    {
        $resArray = [];
        foreach ($promotions as $k => $promotion) {

            $resArray[$k]['promId'] = $promotion->getId();
            $resArray[$k]['userId'] = $promotion->getUserId();
            $resArray[$k]['createdAt'] = $promotion->getCreatedAt()->format('Y-m-d H:i:s');
            $resArray[$k]['promprod'] = $this->getInnerPromprod($promotions, $promotion, $k);
        }
        return $this->getStatisticsInDataStructure($resArray);
    }

    /**
     * Get inner relation.
     */
    private function getInnerPromprod($promotions, $promotion, $k)
    {
        $tempArray = [];
        foreach ($promotion->getPromotionproduct() as $i => $promprod) {
            $tempArray[$i]['productId'] = $promprod->getProduct()->getProductArticle();
            $tempArray[$i]['note'] = $promprod->getNote();
            $tempArray[$i]['prevDate'] = $this->getPrevDate($promotions, $promotion, $k, $promprod);
        }
        return $tempArray;
    }

    /**
     * Get previous date.
     */
    private function getPrevDate($promotions, $promotion, $k, $promprod)
    {
        foreach ($promotions as $key => $value) {
            if ($key != $k) {
                foreach ($value->getPromotionproduct() as $v => $goal) {
                    if ($promprod->getProduct()->getProductArticle() === $goal->getProduct()->getProductArticle()) {
                        if ($promotion->getCreatedAt() < $value->getCreatedAt() && $key == $k-1) {
                            return $value->getCreatedAt()->format('Y-m-d H:i:s');
                        }
                    }
                }
            }
        }
        return null;
    }
}
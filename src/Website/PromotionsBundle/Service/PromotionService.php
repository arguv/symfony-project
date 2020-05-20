<?php

namespace Website\PromotionsBundle\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Promotion Service
 *
 */
class PromotionService
{

    /**
     * Create datas structure.
     */
    public function getDataStructure($promotions)
    {
        $count = count($promotions);
        for ($i = 0; $i < $count-1; $i++) {
            for ($j = 1; $j < $count; $j++) {
                if ($promotions[$i]['prm_id'] !== $promotions[$j]['prm_id']) {
                    if ($promotions[$i]['prd_productArticle'] === $promotions[$j]['prd_productArticle']) {
                        if ( !isset($promotions[$i]['prevDate']) && !isset($promotions[$j]['prevDate'])  ) {
                            $promotions[$j]['prevDate'] = $promotions[$i]['prm_createdAt'];
                        }else{
                            if( $promotions[$i]['prm_createdAt'] > $promotions[$j]['prm_createdAt'] ) {
                                $promotions[$j]['prevDate'] = $promotions[$i]['prm_createdAt'];
                            }
                        }
                    }
                }
            }
        }
        return $promotions;
    }
}
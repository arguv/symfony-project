<?php

namespace Website\PromotionsBundle\Entity;

use Symfony\Component\BrowserKit\Request;


/**
 * PromotionProduct
 */
class PromotionProduct
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $note;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Website\PromotionsBundle\Entity\Promotion
     */
    private $promotion;

    /**
     * @var \Website\PromotionsBundle\Entity\Product
     */
    private $product;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return PromotionProduct
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PromotionProduct
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set promotion
     *
     * @param \Website\PromotionsBundle\Entity\Promotion $promotion
     *
     * @return PromotionProduct
     */
    public function setPromotion(\Website\PromotionsBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return \Website\PromotionsBundle\Entity\Promotion
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set product
     *
     * @param \Website\PromotionsBundle\Entity\Product $product
     *
     * @return PromotionProduct
     */
    public function setProduct(\Website\PromotionsBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Website\PromotionsBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}

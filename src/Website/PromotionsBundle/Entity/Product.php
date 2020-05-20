<?php

namespace Website\PromotionsBundle\Entity;

use Symfony\Component\BrowserKit\Request;


/**
 * Product
 */
class Product
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $productArticle;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $promotionproduct;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->promotionproduct = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set productArticle
     *
     * @param integer $productArticle
     *
     * @return Product
     */
    public function setProductArticle($productArticle)
    {
        $this->productArticle = $productArticle;

        return $this;
    }

    /**
     * Get productArticle
     *
     * @return integer
     */
    public function getProductArticle()
    {
        return $this->productArticle;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product
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
     * Add promotionproduct
     *
     * @param \Website\PromotionsBundle\Entity\PromotionProduct $promotionproduct
     *
     * @return Product
     */
    public function addPromotionproduct(\Website\PromotionsBundle\Entity\PromotionProduct $promotionproduct)
    {
        $this->promotionproduct[] = $promotionproduct;

        return $this;
    }

    /**
     * Remove promotionproduct
     *
     * @param \Website\PromotionsBundle\Entity\PromotionProduct $promotionproduct
     */
    public function removePromotionproduct(\Website\PromotionsBundle\Entity\PromotionProduct $promotionproduct)
    {
        $this->promotionproduct->removeElement($promotionproduct);
    }

    /**
     * Get promotionproduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromotionproduct()
    {
        return $this->promotionproduct;
    }
}

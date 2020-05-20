<?php

namespace Website\PromotionsBundle\Entity;

use Symfony\Component\BrowserKit\Request;


/**
 * Promotion
 */
class Promotion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Promotion
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Promotion
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
     * @return Promotion
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

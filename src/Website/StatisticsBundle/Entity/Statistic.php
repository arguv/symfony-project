<?php

namespace Website\StatisticsBundle\Entity;

use Symfony\Component\BrowserKit\Request;


/**
 * Statistic
 */
class Statistic
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
     * @var integer
     */
    private $clicks = 0;

    /**
     * @var \DateTime
     */
    private $createdAt;


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
     * @return Statistic
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
     * Set clicks
     *
     * @param integer $clicks
     *
     * @return Statistic
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Statistic
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
}

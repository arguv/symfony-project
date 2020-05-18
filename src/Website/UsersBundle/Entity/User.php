<?php

namespace Website\UsersBundle\Entity;

use FOS\UserBundle\Model\User as FosUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends FosUser
{
    public function __construct()
    {
        parent::__construct();

        $this->roles = array('ROLE_USER');
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

}
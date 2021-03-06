<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Privileges
 *
 * @ORM\Table(name="privileges", uniqueConstraints={@ORM\UniqueConstraint(name="controller", columns={"controller", "method", "type"})})
 * @ORM\Entity
 */
class Privileges
{
    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=64, nullable=false)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=64, nullable=false)
     */
    private $method;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=64, nullable=false)
     */
    private $type = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set controller
     *
     * @param string $controller
     *
     * @return Privileges
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set method
     *
     * @param string $method
     *
     * @return Privileges
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Privileges
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
}

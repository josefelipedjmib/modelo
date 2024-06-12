<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * States
 *
 * @ORM\Table(name="states")
 * @ORM\Entity
 */
class States extends AbstractEntity
{
    public function __construct(array $options = array()) 
    {
        parent::__construct($options);
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=95, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="initials", type="string", length=10, nullable=false)
     */
    private $initials;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return States
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set initials.
     *
     * @param string $initials
     *
     * @return States
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * Get initials.
     *
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
    }
}

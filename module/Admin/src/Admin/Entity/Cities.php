<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Cities
 *
 * @ORM\Table(name="cities", indexes={@ORM\Index(name="fk_cities_states_idx", columns={"state_id"})})
 * @ORM\Entity
 */
class Cities extends AbstractEntity
{
    public function __construct(array $options = array()) 
    {
        $this->datahora = new \DateTime();
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
     * @ORM\Column(name="slug", type="string", length=95, nullable=false)
     */
    private $slug;

    /**
     * @var \Admin\Entity\States
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\States")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     * })
     */
    private $state;



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
     * @return Cities
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Cities
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set state.
     *
     * @param \Admin\Entity\States|null $state
     *
     * @return Cities
     */
    public function setState(\Admin\Entity\States $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return \Admin\Entity\States|null
     */
    public function getState()
    {
        return $this->state;
    }
}

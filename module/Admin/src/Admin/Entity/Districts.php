<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Districts
 *
 * @ORM\Table(name="districts", indexes={@ORM\Index(name="fk_districts_city_idx", columns={"city_id"})})
 * @ORM\Entity
 */
class Districts extends AbstractEntity
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
     * @var \Admin\Entity\Cities
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Cities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $city;



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
     * @return Districts
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
     * @return Districts
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
     * Set city.
     *
     * @param \Admin\Entity\Cities|null $city
     *
     * @return Districts
     */
    public function setCity(\Admin\Entity\Cities $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return \Admin\Entity\Cities|null
     */
    public function getCity()
    {
        return $this->city;
    }
}

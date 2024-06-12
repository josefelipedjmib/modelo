<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;
/**
 * AddressSearchs
 *
 * @ORM\Table(name="address_searchs", indexes={@ORM\Index(name="fk_adrress_searchs_cities1_idx", columns={"city_id"})})
 * @ORM\Entity
 */
class AddressSearchs extends AbstractEntity
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
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postal_code", type="string", length=15, nullable=true)
     */
    private $postalCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="latitude", type="string", length=20, nullable=true)
     */
    private $latitude;

    /**
     * @var string|null
     *
     * @ORM\Column(name="longitude", type="string", length=20, nullable=true)
     */
    private $longitude;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ddd", type="integer", nullable=true)
     */
    private $ddd;

    /**
     * @var \Admin\Entity\Districts
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Districts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     * })
     */
    private $district;

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
     * Set address.
     *
     * @param string|null $address
     *
     * @return AddressSearchs
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postalCode.
     *
     * @param string|null $postalCode
     *
     * @return AddressSearchs
     */
    public function setPostalCode($postalCode = null)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set latitude.
     *
     * @param string|null $latitude
     *
     * @return AddressSearchs
     */
    public function setLatitude($latitude = null)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return string|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param string|null $longitude
     *
     * @return AddressSearchs
     */
    public function setLongitude($longitude = null)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return string|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set ddd.
     *
     * @param int|null $ddd
     *
     * @return AddressSearchs
     */
    public function setDdd($ddd = null)
    {
        $this->ddd = $ddd;

        return $this;
    }

    /**
     * Get ddd.
     *
     * @return int|null
     */
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * Set district.
     *
     * @param \Admin\Entity\Districts|null $district
     *
     * @return AddressSearchs
     */
    public function setDistrict(\Admin\Entity\Districts $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district.
     *
     * @return \Admin\Entity\Districts|null
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set city.
     *
     * @param \Admin\Entity\Cities|null $city
     *
     * @return AddressSearchs
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

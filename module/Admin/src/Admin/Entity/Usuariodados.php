<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Usuariodados
 *
 * @ORM\Table(name="usuariodados")
 * @ORM\Entity
 */
class Usuariodados extends AbstractEntity
{
    public function __construct(array $options = array()) 
    {
        parent::__construct($options);
    }

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datanasci", type="date", nullable=true)
     */
    private $datanasci;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="sexo", type="integer", nullable=true)
     */
    private $sexo;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="exibir", type="boolean", nullable=true)
     */
    private $exibir = '0';

    /**
     * @var \Admin\Entity\Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Admin\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     * })
     */
    private $usuario;



    /**
     * Set datanasci.
     *
     * @param \DateTime|null $datanasci
     *
     * @return Usuariodados
     */
    public function setDatanasci($datanasci = null)
    {
        $this->datanasci = $datanasci;

        return $this;
    }

    /**
     * Get datanasci.
     *
     * @return \DateTime|null
     */
    public function getDatanasci()
    {
        return $this->datanasci;
    }

    /**
     * Set sexo.
     *
     * @param integer|null $sexo
     *
     * @return Usuariodados
     */
    public function setSexo($sexo = null)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo.
     *
     * @return integer|null
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set exibir.
     *
     * @param bool|null $exibir
     *
     * @return Usuariodados
     */
    public function setExibir($exibir = null)
    {
        $this->exibir = $exibir;

        return $this;
    }

    /**
     * Get exibir.
     *
     * @return bool|null
     */
    public function getExibir()
    {
        return $this->exibir;
    }

    /**
     * Set usuario.
     *
     * @param \Admin\Entity\Usuario $usuario
     *
     * @return Usuariodados
     */
    public function setUsuario(\Admin\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \Admin\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Usuariotelefone
 *
 * @ORM\Table(name="usuariotelefone", indexes={@ORM\Index(name="telefone", columns={"id"}), @ORM\Index(name="usuario", columns={"usuario"})})
 * @ORM\Entity
 */
class Usuariotelefone extends AbstractEntity
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
     * @var bool
     *
     * @ORM\Column(name="movel", type="boolean", nullable=false)
     */
    private $movel = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=30, nullable=false)
     */
    private $telefone;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="exibir", type="boolean", nullable=true)
     */
    private $exibir = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ewhatsapp", type="boolean", nullable=true)
     */

    private $ewhatsapp = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="etelegram", type="boolean", nullable=true)
     */
    private $etelegram = '0';

    /**
     * @var \Admin\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     * })
     */
    private $usuario;



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
     * Set movel.
     *
     * @param bool $movel
     *
     * @return Usuariotelefone
     */
    public function setMovel($movel)
    {
        $this->movel = $movel;

        return $this;
    }

    /**
     * Get movel.
     *
     * @return bool
     */
    public function getMovel()
    {
        return $this->movel;
    }

    /**
     * Set telefone.
     *
     * @param string $telefone
     *
     * @return Usuariotelefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone.
     *
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set exibir.
     *
     * @param bool|null $exibir
     *
     * @return Usuariotelefone
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
     * Set ewhatsapp.
     *
     * @param bool|null $ewhatsapp
     *
     * @return Usuariotelefone
     */
    public function setEwhatsapp($ewhatsapp = null)
    {
        $this->ewhatsapp = $ewhatsapp;

        return $this;
    }

    /**
     * Get ewhatsapp.
     *
     * @return bool|null
     */
    public function getEwhatsapp()
    {
        return $this->ewhatsapp;
    }

    /**
     * Set etelegram.
     *
     * @param bool|null $etelegram
     *
     * @return Usuariotelefone
     */
    public function setEtelegram($etelegram = null)
    {
        $this->etelegram = $etelegram;

        return $this;
    }

    /**whatsapp
     * Get etelegram.
     *
     * @return bool|null
     */
    public function getEtelegram()
    {
        return $this->etelegram;
    }

    /**
     * Set usuario.
     *
     * @param \Admin\Entity\Usuario|null $usuario
     *
     * @return Usuariotelefone
     */
    public function setUsuario(\Admin\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \Admin\Entity\Usuario|null
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

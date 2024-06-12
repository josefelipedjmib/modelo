<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Logsystem
 *
 * @ORM\Table(name="logsystem", indexes={@ORM\Index(name="usuario_registro", columns={"usuario"})})
 * @ORM\Entity
 */
class Logsystem extends AbstractEntity
{
    public function __construct(array $options = array()) 
    {
        $this->datahora = new \DateTime();
        parent::__construct($options);
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="acao", type="string", length=20, nullable=true)
     */
    private $acao;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datahora", type="datetime", nullable=false)
     */
    private $datahora;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ipa", type="boolean", nullable=true)
     */
    private $ipa;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ipb", type="boolean", nullable=true)
     */
    private $ipb;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ipc", type="boolean", nullable=true)
     */
    private $ipc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ipd", type="boolean", nullable=true)
     */
    private $ipd;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set acao
     *
     * @param string $acao
     *
     * @return Logsystem
     */
    public function setAcao($acao)
    {
        $this->acao = $acao;

        return $this;
    }

    /**
     * Get acao
     *
     * @return string
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Logsystem
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set datahora
     *
     * @param \DateTime $datahora
     *
     * @return Logsystem
     */
    public function setDatahora($datahora)
    {
        $this->datahora = $datahora;

        return $this;
    }

    /**
     * Get datahora
     *
     * @return \DateTime
     */
    public function getDatahora()
    {
        return $this->datahora;
    }

    /**
     * Set ipa
     *
     * @param boolean $ipa
     *
     * @return Logsystem
     */
    public function setIpa($ipa)
    {
        $this->ipa = $ipa;

        return $this;
    }

    /**
     * Get ipa
     *
     * @return boolean
     */
    public function getIpa()
    {
        return $this->ipa;
    }

    /**
     * Set ipb
     *
     * @param boolean $ipb
     *
     * @return Logsystem
     */
    public function setIpb($ipb)
    {
        $this->ipb = $ipb;

        return $this;
    }

    /**
     * Get ipb
     *
     * @return boolean
     */
    public function getIpb()
    {
        return $this->ipb;
    }

    /**
     * Set ipc
     *
     * @param boolean $ipc
     *
     * @return Logsystem
     */
    public function setIpc($ipc)
    {
        $this->ipc = $ipc;

        return $this;
    }

    /**
     * Get ipc
     *
     * @return boolean
     */
    public function getIpc()
    {
        return $this->ipc;
    }

    /**
     * Set ipd
     *
     * @param boolean $ipd
     *
     * @return Logsystem
     */
    public function setIpd($ipd)
    {
        $this->ipd = $ipd;

        return $this;
    }

    /**
     * Get ipd
     *
     * @return boolean
     */
    public function getIpd()
    {
        return $this->ipd;
    }

    /**
     * Set usuario
     *
     * @param \Admin\Entity\Usuario $usuario
     *
     * @return Logsystem
     */
    public function setUsuario(\Admin\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
        $usuario->addRegistros($this);
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Admin\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

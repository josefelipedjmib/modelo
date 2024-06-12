<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Usuarioendereco
 *
 * @ORM\Table(name="usuarioendereco", indexes={@ORM\Index(name="endereco", columns={"endereco"}), @ORM\Index(name="endereco_usuario", columns={"usuario"})})
 * @ORM\Entity
 */
class Usuarioendereco extends AbstractEntity
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
     * @ORM\Column(name="numero", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $numero;

    /**
     * @var string|null
     *
     * @ORM\Column(name="complemento", type="string", length=100, nullable=true)
     */
    private $complemento;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="exibir", type="boolean", nullable=true)
     */
    private $exibir = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia", type="string", length=50, nullable=true)
     */
    private $referencia;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nome", type="string", length=20, nullable=true)
     */
    private $nome;

    /**
     * @var \Admin\Entity\AddressSearchs
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\AddressSearchs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="endereco", referencedColumnName="id")
     * })
     */
    private $endereco;

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
     * Set numero.
     *
     * @param string|null $numero
     *
     * @return Usuarioendereco
     */
    public function setNumero($numero = null)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero.
     *
     * @return string|null
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set complemento.
     *
     * @param string|null $complemento
     *
     * @return Usuarioendereco
     */
    public function setComplemento($complemento = null)
    {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Get complemento.
     *
     * @return string|null
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set exibir.
     *
     * @param bool|null $exibir
     *
     * @return Usuarioendereco
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
     * Set referencia.
     *
     * @param string|null $referencia
     *
     * @return Usuarioendereco
     */
    public function setReferencia($referencia = null)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia.
     *
     * @return string|null
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set nome.
     *
     * @param string|null $nome
     *
     * @return Usuarioendereco
     */
    public function setNome($nome = null)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome.
     *
     * @return string|null
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set endereco.
     *
     * @param \Admin\Entity\AddressSearchs|null $endereco
     *
     * @return Usuarioendereco
     */
    public function setEndereco(\Admin\Entity\AddressSearchs $endereco = null)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get endereco.
     *
     * @return \Admin\Entity\AddressSearchs|null
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set usuario.
     *
     * @param \Admin\Entity\Usuario|null $usuario
     *
     * @return Usuarioendereco
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

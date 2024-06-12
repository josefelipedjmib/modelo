<?php

namespace AdminSocial\Entity;

use Doctrine\ORM\Mapping as ORM,
    Base\Entity\AbstractEntity;

/**
 * Usuariosocial
 *
 * @ORM\Table(name="usuariosocial", indexes={@ORM\Index(name="usuario_social", columns={"usuario"})})
 * @ORM\Entity(repositoryClass="AdminSocial\Entity\UsuariosocialRepositorio")
 */
class Usuariosocial extends AbstractEntity
{
    public function __construct(array $options = array()) 
    {
        parent::__construct($options);
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="oauth_uid", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $oauthUid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_provider", type="string", length=6, precision=0, scale=0, nullable=false, unique=false)
     */
    private $oauthProvider;

    /**
     * @var string|null
     *
     * @ORM\Column(name="oauth_profile", type="string", length=200, precision=0, scale=0, nullable=true, unique=false)
     */
    private $oauthProfile;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="exibir", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $exibir;

    /**
     * @var \Admin\Entity\Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Usuario", inversedBy="sociais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=true)
     * })
     */
    private $usuario;


    /**
     * Set oauthUid.
     *
     * @param string $oauthUid
     *
     * @return Usuariosocial
     */
    public function setOauthUid($oauthUid)
    {
        $this->oauthUid = $oauthUid;

        return $this;
    }

    /**
     * Get oauthUid.
     *
     * @return string
     */
    public function getOauthUid()
    {
        return $this->oauthUid;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Usuariosocial
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set oauthProvider.
     *
     * @param string $oauthProvider
     *
     * @return Usuariosocial
     */
    public function setOauthProvider($oauthProvider)
    {
        $this->oauthProvider = $oauthProvider;

        return $this;
    }

    /**
     * Get oauthProvider.
     *
     * @return string
     */
    public function getOauthProvider()
    {
        return $this->oauthProvider;
    }

    /**
     * Set oauthProfile.
     *
     * @param string|null $oauthProfile
     *
     * @return Usuariosocial
     */
    public function setOauthProfile($oauthProfile = null)
    {
        $this->oauthProfile = $oauthProfile;

        return $this;
    }

    /**
     * Get oauthProfile.
     *
     * @return string|null
     */
    public function getOauthProfile()
    {
        return $this->oauthProfile;
    }

    /**
     * Set exibir.
     *
     * @param bool|null $exibir
     *
     * @return Usuariosocial
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
     * @return Usuariosocial
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

<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

use Zend\Math\Rand,
    Zend\Crypt\Key\Derivation\Pbkdf2;

use Doctrine\Common\Collections\Criteria,
    Base\Entity\AbstractEntity;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Admin\Entity\UsuarioRepositorio")
 */
class Usuario extends AbstractEntity
{
    public function __construct(array $options = array()) 
    {
        $this->enderecos = new ArrayCollection();
        $this->noticias = new ArrayCollection();
        $this->registros = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->servico = new ArrayCollection();
        $this->sociais = new ArrayCollection();
        $this->telefones = new ArrayCollection();
        $this->setCreatedAt();
        $this->salt = $this->generateRandomKey();
        $this->setSenha($this->salt.$this->generateRandomKey());
        parent::__construct($options);
    }

    public function regenerateActivationKey()
    {
        $this->activationkey = md5($this->email.$this->generateRandomKey());
    }

    public function generateRandomKey(){
        return base64_encode(Rand::getBytes(8, true));
    }

    public function encryptPassword($password)
    {
        return base64_encode(Pbkdf2::calc('sha256', $password, $this->salt, 2012, strlen($password)*2));
    }

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=50, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="snome", type="string", length=50, nullable=true)
     */
    private $snome;

    /**
     * @var string
     *
     * @ORM\Column(name="nomeExibicao", type="string", length=30, nullable=true)
     */
    private $nomeexibicao;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="cpf", type="integer", nullable=false)
     */
    private $cpf;

    /**
     * @var string
     *
     * @ORM\Column(name="senha", type="string", nullable=true)
     */
    private $senha;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activate", type="boolean", nullable=true)
     */
    private $activate;

    /**
     * @var string
     *
     * @ORM\Column(name="activationKey", type="string", length=255, nullable=false)
     */
    private $activationkey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="exibir", type="boolean", nullable=true)
     */
    private $exibir = '0';


    /**
     * @var \Admin\Entity\Usuariodados
     *
     * @ORM\OneToOne(targetEntity="Admin\Entity\Usuariodados", mappedBy="usuario",cascade={"persist"}, orphanRemoval=true)
     */
    private $dados;

    /**
     * @var \Admin\Entity\Usuarioendereco
     *
     * @ORM\OneToMany(targetEntity="Admin\Entity\Usuarioendereco", mappedBy="usuario")
     */
    private $enderecos;

    /**
     * @var \Admin\Entity\Logsystem
     *
     * @ORM\OneToMany(targetEntity="Admin\Entity\Logsystem", mappedBy="usuario",cascade={"persist"}, orphanRemoval=false)
     */
    private $registros;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Acl\Entity\Role", inversedBy="usuario")
     */
    private $role;


    /**
     * @var \AdminSocial\Entity\Usuariosocial
     *
     * @ORM\OneToMany(targetEntity="AdminSocial\Entity\Usuariosocial", mappedBy="usuario",cascade={"persist"}, orphanRemoval=true)
     */
    private $sociais;

    /**
     * @var \Admin\Entity\Usuariotelefone
     *
     * @ORM\OneToMany(targetEntity="Admin\Entity\Usuariotelefone", mappedBy="usuario",cascade={"persist"}, orphanRemoval=true)
     */
    private $telefones;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Usuario
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Usuario
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set snome
     *
     * @param string $snome
     *
     * @return Usuario
     */
    public function setSnome($snome)
    {
        $this->snome = $snome;

        return $this;
    }

    /**
     * Get snome
     *
     * @return string
     */
    public function getSnome()
    {
        return $this->snome;
    }

    /**
     * Set nomeexibicao
     *
     * @param string $nomeexibicao
     *
     * @return Usuario
     */
    public function setNomeexibicao($nomeexibicao)
    {
        $this->nomeexibicao = $nomeexibicao;

        return $this;
    }

    /**
     * Get nomeexibicao
     *
     * @return string
     */
    public function getNomeexibicao()
    {
        return $this->nomeexibicao;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }    
    
    /**
     * Set cpf
     *
     * @param integer $cpf
     *
     * @return Usuario
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
    * Get cpf
    *
    * @return string
    */
   public function getCpf()
   {
       return str_pad($this->cpf, 11, '0', STR_PAD_LEFT);
   }

    /**
     * Get by email
     *
     * @param string $email
     * @return ArrayCollection
     */
    public function getByEmail($email)
    {
        $criteria = Criteria::create()
        ->where(Criteria::expr()->eq("email", $email));
        return $this->getSociais()->matching($criteria);
    }

    /**
     * Set senha
     *
     * @param string $senha
     *
     * @return Usuario
     */
    public function setSenha($senha)
    {
        $this->regenerateActivationKey();
        $this->senha = $this->encryptPassword($senha);
        return $this;
    }

    /**
     * Get senha
     *
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set activate
     *
     * @param boolean $activate
     *
     * @return Usuario
     */
    public function setActivate($activate)
    {
        $this->activate = $activate;

        return $this;
    }

    /**
     * Get activate
     *
     * @return boolean
     */
    public function getActivate()
    {
        return $this->activate;
    }

    /**
     * Set activationkey
     *
     * @param string $activationkey
     *
     * @return Usuario
     */
    public function setActivationkey($activationkey)
    {
        $this->activationkey = $activationkey;

        return $this;
    }

    /**
     * Get activationkey
     *
     * @return string
     */
    public function getActivationkey()
    {
        return $this->activationkey;
    }

    /**
     * Set updatedat
     *
     * @return Usuario
     */
    public function setUpdatedat()
    {
        $this->updatedat = new \DateTime("now");

        return $this;
    }

    /**
     * Get updatedat
     *
     * @return \DateTime
     */
    public function getUpdatedat()
    {
        return $this->updatedat;
    }

    /**
     * Set createdat
     *
     * @return Usuario
     */
    public function setCreatedat()
    {
        $this->createdat = new \DateTime("now");

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set exibir
     *
     * @param boolean $exibir
     *
     * @return Usuario
     */
    public function setExibir($exibir)
    {
        $this->exibir = $exibir;

        return $this;
    }

    /**
     * Get exibir
     *
     * @return boolean
     */
    public function getExibir()
    {
        return $this->exibir;
    }

    /**
     * Set dados
     *
     * @param \Admin\Entity\Usuariodados $dados
     *
     * @return Usuario
     */
    public function setDados(\Admin\Entity\Usuariodados $dados)
    {
        $this->dados = $dados;
        return $this;
    }

    /**
     * Get dados
     *
     * @return \Admin\Entity\Usuariodados
     */
    public function getDados()
    {
        return $this->dados ?? new Usuariodados([
            "Datanasci" => "0000-00-00",
            "Sexo" => "0"
        ]);
    }

    /**
     * Set enderecos
     *
     * @param \Admin\Entity\Usuarioendereco $endereco
     *
     * @return Usuario
     */
    public function setEnderecos(\Admin\Entity\Usuarioendereco $endereco)
    {
        $this->enderecos = $endereco;
        return $this;
    }

    /**
     * Get enderecos
     *
     * @return \Admin\Entity\Usuarioendereco
     */
    public function getEnderecos()
    {
        return $this->enderecos;
    }

    
    /**
     * Add registros
     *
     * @param \Admin\Entity\Logsystem $registro
     *
     * @return Usuario
     */
    public function addRegistros(\Admin\Entity\Logsystem $registro)
    {
        $this->registros[] = $registro;
        return $this;
    }
    
    /**
     * Remove registros
     *
     * @param \Admin\Entity\Logsystem $registro
     */
    public function removeRegistros(\Admin\Entity\Logsystem $registro)
    {
        $this->registros->removeElement($registro);
    }

    /**
     * Get registros
     *
     * @return ArrayCollection
     */
    public function getRegistros()
    {
        return $this->registros;
    }

    /**
     * Add role
     *
     * @param \Acl\Entity\Role $role
     *
     * @return Usuario
     */
    public function addRole(\Acl\Entity\Role $role)
    {
        $this->role[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Acl\Entity\Role $role
     */
    public function removeRole(\Acl\Entity\Role $role)
    {
        $this->role->removeElement($role);
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add sociais
     *
     * @param \AdminSocial\Entity\Usuariosocial $sociais
     *
     * @return Usuario
     */
    public function addSociais(\AdminSocial\Entity\Usuariosocial $sociais)
    {
        $this->sociais[] = $sociais;
        return $this;
    }

    /**
     * Remove sociais
     *
     * @param \AdminSocial\Entity\Usuariosocial $sociais
     */
    public function removeSociais(\AdminSocial\Entity\Usuariosocial $sociais)
    {
        $this->sociais->removeElement($sociais);
    }

    /**
     * Get sociais
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSociais()
    {
        return $this->sociais;
    }

    /**
     * Add telefones
     *
     * @param \Admin\Entity\Usuariotelefones $telefones
     *
     * @return Usuario
     */
    public function addTelefones(\Admin\Entity\Usuariotelefone $telefones)
    {
        if(
            empty(
                $this->getByTelefone($telefones->getTelefone()))
        )
            $this->telefones[] = $telefones;

        return $this;
    }

    /**
     * Remove telefones
     *
     * @param \Admin\Entity\Usuariotelefones $telefones
     */
    public function removeTelefones(\Admin\Entity\Usuariotelefone $telefones)
    {
        $this->telefones->removeElement($telefones);
    }

    /**
     * Remove telefones by telefone
     *
     * @param string $telefone
     */
    public function removeByTelefone($telefone)
    { 
        $this->telefones->removeElement(
            $this->getByTelefone($telefone)[0]
        );
    }


    /**
     * Get telefones
     *
     * @return ArrayCollection
     */
    public function getTelefones()
    {
        return $this->telefones;
    }

    /**
     * Get by telefone
     *
     * @param string $telefone
     * @return ArrayCollection
     */
    public function getByTelefone($telefone)
    {
        $criteria = Criteria::create()
        ->where(Criteria::expr()->eq("telefone", $telefone));
        return $this->getTelefones()->matching($criteria);
    }

    /**
    * Pre persistencia
    *
    * @ORM\PrePersist
    */
    public function prePersist(){
        $this->setUpdatedAt();
    }
}

<?php
/*
    Autor: benhur alencar azevedo
    utilidade: gera o model Usuario
 */
namespace lib\models;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nome;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $hashSenha;

    /**
     * @var Collection<int, Contato>
     * @ORM\OneToMany(targetEntity=Contato::class, mappedBy="usuario")
     */
    private $contatos;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void 
    {
        $this->nome = $nome;
    }

    public function getEmail(): string 
    {
        return $this->email;
    }

    public function setEmail(string $email): void 
    {
        $this->email = $email;
    }

    public function getHashSenha(): string 
    {
        return $this->hashSenha;
    }

    public function setHashSenha(string $hashSenha): void 
    {
        $this->hashSenha = $hashSenha;
    }

    public function __construct()
    {
        $this->contatos = new ArrayCollection();
    }

    public function addContato(Contato $contato): void 
    {
        $this->contatos->add($contato);
        $contato->setUsuario($this);
    }

    public function getContatos(): Collection
    {
        return $this->contatos;
    }
}
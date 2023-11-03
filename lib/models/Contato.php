<?php
/*
    Autor: benhur alencar azevedo
    utilidade: gera o model contato
 */
namespace lib\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="contatos")
 */
class Contato
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
     * @ORM\Column(type="string")
     */
    private $telefone;

    /**
     * @ORM\ManyToOne(Usuario::class, inversedBy="contatos")
     */
    private $usuario;

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

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function setTelefone($telefone): void 
    {
        $this->telefone = $telefone;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void 
    {
        $this->usuario = $usuario;
    }
    public function toArray(): array
    {
        $array = [];
        $array["nome"] = mb_convert_encoding($this->getNome(), 'UTF-8', mb_list_encodings());
        $array["telefone"] = mb_convert_encoding($this->getTelefone(), 'UTF-8', mb_list_encodings());
        $array["usuario_id"] = $this->getUsuario()->getId();
        $array["id"] = $this->getId();
        return $array;
    }
}
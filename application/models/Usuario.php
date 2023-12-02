<?php

namespace Application\models;

class Usuario
{
    private $id;
    private $nome;
    private $cpf;
    private $email;
    private $senha;


    public function __construct($nome, $cpf, $email, $senha)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }


    public function getNome()
    {
        return $this->nome;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getSenha()
    {
        return $this->senha;
    }
}

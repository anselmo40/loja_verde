<?php

namespace Application\models;

class Produto
{
    private $codigo;
    private $nome;
    private $marca;
    private $preco;
    private $imagem_url;
    public function __construct($nome, $marca, $preco, $imagem_url = null)
    {
        $this->nome = $nome;
        $this->marca = $marca;
        $this->preco = $preco;
        $this->imagem_url = $imagem_url;
    }
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function setImagem_url($imagem_url)
    {
        $this->imagem_url = $imagem_url;
    }

    public function getImagem_url()
    {
        return $this->imagem_url;
    }
}

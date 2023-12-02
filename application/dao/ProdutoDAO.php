<?php

namespace Application\dao;

use Application\models\Produto;

class ProdutoDAO
{
    private $conexao;

    public function __construct()
    {
        try {
            $this->conexao = new \PDO("mysql:host=localhost;dbname=loja", "root", "153896");
            $this->conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
    }

    public function salvar($produto)
    {
        try {
            $nome = $produto->getNome();
            $marca = $produto->getMarca();
            $preco = $produto->getPreco();
            $imagem_url = $produto->getImagem_url();

            $stmt = $this->conexao->prepare("INSERT INTO produtos(nome, marca, preco, imagem_url) VALUES (:nome, :marca, :preco, :imagem_url)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':imagem_url', $imagem_url);

            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            echo "Erro ao salvar produto: " . $e->getMessage();
            return false;
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->conexao->query("SELECT * FROM produtos");
            $produtos = [];

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $produto = new Produto($row["nome"], $row["marca"], $row["preco"], $row["imagem_url"]);
                $produto->setCodigo($row["codigo"]);
                array_push($produtos, $produto);
            }

            return $produtos;
        } catch (\PDOException $e) {
            echo "Erro ao recuperar produtos: " . $e->getMessage();
            return [];
        }
    }

    public function findById($id)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM produtos WHERE codigo = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            $produto = new Produto(
                $row["nome"] ?? null,
                $row["marca"] ?? null,
                $row["preco"] ?? null,
                $row["imagem_url"] ?? null
            );


            return $produto;
        } catch (\PDOException $e) {
            echo "Erro ao recuperar produto por ID: " . $e->getMessage();
            return null;
        }
    }

    public function atualizar($codigo, $nome, $marca, $preco, $imagem_url)
{
    try {
        // Verifique se o código do produto é válido antes de continuar
        if (!$codigo) {
            throw new \PDOException("Código do produto inválido.");
        }

        // Verifique se o produto existe antes de prosseguir com a atualização
        if (!$this->findById($codigo)) {
            throw new \PDOException("Produto não encontrado para o código: $codigo");
        }

        $stmt = $this->conexao->prepare("UPDATE produtos SET nome = :nome, marca = :marca, preco = :preco, imagem_url = :imagem_url WHERE codigo = :codigo");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':imagem_url', $imagem_url);
        $stmt->bindParam(':codigo', $codigo);

        // Verifique se a execução da consulta foi bem-sucedida
        if (!$stmt->execute()) {
            throw new \PDOException("Erro ao executar a consulta de atualização. Detalhes: " . implode(' ', $stmt->errorInfo()));
        }

        // Verifique se a atualização afetou alguma linha (se encontrou o produto)
        if ($stmt->rowCount() > 0) {
            return $this->findById($codigo);
        } else {
            throw new \PDOException("Nenhum produto encontrado para o código: $codigo");
        }
    } catch (\PDOException $e) {
        echo "Erro ao atualizar produto: " . $e->getMessage();
        return null;
    }
}


    public function deletar($id)
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM produtos WHERE codigo = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            echo "Erro ao deletar produto: " . $e->getMessage();
            return false;
        }
    }
}

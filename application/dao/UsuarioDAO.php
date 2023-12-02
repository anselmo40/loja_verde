<?php

namespace Application\dao;

use Application\models\Usuario;

class UsuarioDAO
{

    private $conexao;
    public function __construct()
    {
        $this->conexao = new Conexao();
    }
    public function salvar($usuario)
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();

        $conn = $this->conexao->getConexao();

        $nome = $conn->real_escape_string($usuario->getNome());
        $cpf = $conn->real_escape_string($usuario->getCpf());
        $email = $conn->real_escape_string($usuario->getEmail());
        $senha = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);


        $SQL = "INSERT INTO usuarios(nome, cpf, email, senha) 
                    VALUES ('$nome', '$cpf', '$email', '$senha')";

        try {
            if ($conn->query($SQL) === TRUE) {
                return true;
            } else {
                throw new \Exception("Erro ao cadastrar usuário: " . $conn->error);
            }
        } catch (\Exception $e) {

            return false;
        }
    }

    public function findAll()
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();
        $SQL = "SELECT * FROM usuarios";
        $result = $conn->query($SQL);
        $usuarios = [];

        while ($row = $result->fetch_assoc()) {
            $usuario = new Usuario(
                $row["nome"],
                $row["cpf"],
                $row["email"],
                $row["senha"]
            );
            $usuario->setId($row["id"]);
            array_push($usuarios, $usuario);
        }

        return $usuarios;
    }

    // Retrieve (R)
    public function findById($id)
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();

        $SQL = "SELECT * FROM usuarios WHERE id =" . $id;

        $result = $conn->query($SQL);
        $row = $result->fetch_assoc();

        if ($row) {
            $usuario = new Usuario(
                $row["nome"],
                $row["cpf"],
                $row["email"],
                $row["senha"]

            );
            $usuario->setId($row["id"]);
            return $usuario;
        }
        return null;
    }

    public function buscarPorTermo($termo)
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();

        // Use prepared statements para evitar injeção de SQL
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nome LIKE ?");
        $termo = "%" . $termo . "%";
        $stmt->bind_param("s", $termo);
        $stmt->execute();

        $result = $stmt->get_result();

        $usuarios = [];

        while ($row = $result->fetch_assoc()) {
            $usuario = new Usuario($row["nome"], $row["cpf"], $row["email"], $row["senha"]);
            $usuario->setId($row["id"]);
            array_push($usuarios, $usuario);
        }

        $stmt->close();

        return $usuarios;
    }


    public function buscarPorEmail($email)
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();

        $email = $conn->real_escape_string($email);

        $SQL = "SELECT * FROM usuarios WHERE email = '$email'";

        try {
            $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $usuario = new Usuario(
                    $row['nome'],
                    $row['cpf'],
                    $row['email'],
                    $row['senha']
                );

                return $usuario;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }



    // Update (U)
    public function atualizar($usuario)
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();

        $codigo = $usuario->getId();
        $nome = $usuario->getNome();
        $cpf = $usuario->getCpf();
        $email = $usuario->getEmail();
        $senha = $usuario->getSenha();

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, cpf = ?, email = ?, senha = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nome, $cpf, $email, $senha, $id);

        if ($stmt->execute()) {
            $stmt->close();
            return $this->findById($id);
        } else {
            $stmt->close();
            echo "Error: " . $stmt->error;
            return $usuario;
        }
    }

    // Delete (D)
    public function deletar($id)
    {
        $conexao = new Conexao();
        $conn = $conexao->getConexao();

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
}

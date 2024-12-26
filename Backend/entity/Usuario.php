<?php

class Usuario
{
    private $id;
    private $nome;
    private $senha;
    private $email;
    private $token;
    private $roles; // Nova propriedade para o nível do usuário
    private $criado;
    private $atualizado;

    public function __construct($id, $nome, $senha, $email, $token, $roles = 'Admin', $criado, $atualizado)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->senha = $senha;
        $this->email = $email;
        $this->token = $token;
        $this->roles = $roles;
        $this->criado = $criado;
        $this->atualizado = $atualizado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getRole()
    {
        return $this->roles;
    }

    public function getCriado()
    {
        return $this->criado;
    }

    public function getAtualizado()
    {
        return $this->atualizado;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setRole($roles)
    {
        $this->roles = $roles;
    }
}
?>

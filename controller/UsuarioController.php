<?php
    require_once("./config/Token.php");


class UsuarioController
{
    private $usuario;


    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function cadastrar($parametros)
    {
        $parametrosPost = parametrosJson() + parametrosPost();
        if (!verificarParametrosObrigatorios($parametrosPost, ["username", "senha", "nome", "datanasc"]));
        $usuarioUsername = $this->usuario->listarUsername($parametrosPost["username"]);
        if ($usuarioUsername) outputError(404, "Usuario ja existe");
        $usuario = $this->usuario->cadastrar($parametrosPost["nome"], $parametrosPost["username"], $parametrosPost["senha"], $parametrosPost["datanasc"]);
        if (!$usuario) outputError(500, "Erro interno do servidor");
        output(200, "Usuario cadastrado com sucesso", "OK", $usuario);
    }

    public function login($parametros)
    {
        $parametrosPost = parametrosJson() + parametrosPost();
        if (!verificarParametrosObrigatorios($parametrosPost, ["username", "senha"]));
        $usuario = $this->usuario->dadosUsuarioUsernameSenha($parametrosPost["username"], $parametrosPost["senha"]);
        if (!$usuario) outputError(401, "Username ou senha incorreta");
        $token = Token::criarToken(["id" => $usuario["id"], "username" => $usuario["username"]]);
        $dados = ['token' => $token];
        output(200, "Usuario logado com sucesso", $dados);
    }

    public function logout($parametros)
    {
        $informacoesToken = validarToken();
        $chaveJWT = isset($informacoesToken["token"]) ? $informacoesToken["token"] : null;

        Token::logout($chaveJWT);

        setcookie('token', '', time() - 3600, '/');

        output(200, "Usuario deslogado com sucesso");
    }
    
    public function perfil($parametros)
    {
        $informacoesToken = validarToken();
        $usuario = $this->usuario->listar($informacoesToken["id"]);
        if (!$usuario) outputError(401, "Perfil não encontrado");
        output(200, "Perfil encontrado com sucesso", $usuario);
    }
    
    public function editar($parametros)
    {
        $informacoesToken = validarToken();
        $parametrosBody = parametrosJson() + parametrosPost();
        if (!verificarParametrosObrigatorios($parametrosBody, ["nome", "datanasc", "senha"]));
        $data_nasc = strtotime($parametrosBody["datanasc"]);
        $data_nascFormatado = date('Y-m-d H:i:s', $data_nasc);
        $usuarioAntigo = $this->usuario->listarCompleto($informacoesToken["id"]);
        if ($usuarioAntigo && $usuarioAntigo["nome"] == $parametrosBody["nome"] && $usuarioAntigo["datanasc"] == $parametrosBody["datanasc"] && password_verify($parametrosBody["senha"], $usuarioAntigo["senha"])) {
            outputError(400, "Nenhum dado foi alterado");
        }
        $usuario = $this->usuario->update($parametrosBody["nome"], $data_nascFormatado, $parametrosBody["senha"], $informacoesToken["id"]);
        if (!$usuario) outputError(401, "Usuario não encontrado");
        output(200, "Usuario editado com sucesso", $usuario);
    }
}

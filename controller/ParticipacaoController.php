<?php

class ParticipacaoController
{
    private $participacao;
    private $usuario;
    private $evento;

    public function __construct($participacao)
    {
        $this->participacao = $participacao;
        $this->usuario = new Usuario();
        $this->evento = new Evento();
    }

    public function cadastrarParticipacao($parametros)
    { // POST
        $informacoesToken = validarToken();
        $parametrosPost = parametrosJson() + parametrosPost();
        $timestamp = time();
        if (!verificarParametrosObrigatorios($parametrosPost, ["idEvento"]));

        $participacaoId = $this->participacao->listarEventoUsuario($parametrosPost["idEvento"], $informacoesToken["id"]);
        $usuarioId = $this->usuario->listar($informacoesToken["id"]);
        $eventoId = $this->evento->listar($parametrosPost["idEvento"]);
        if (!$usuarioId) outputError(404, "Usuario nao encontrado");
        if (!$eventoId) outputError(404, "Evento nao encontrado");
        if ($participacaoId) outputError(404, "Participacao ja existe");

        $participacao = $this->participacao->cadastrar(
            $informacoesToken["id"],
            $parametrosPost["idEvento"],
            $timestamp
        );  

        if (!$participacao) {
            outputError(500, "Erro interno do servidor");
        }

        output(200, "Participação cadastrada com sucesso", "OK", $participacao);
    }

    


    public function buscarPorIdUsuario($parametros){
        $informacoesToken = validarToken();
        $participacao = $this->participacao->listar($informacoesToken["id"]);
        if(!$participacao){
            outputError(404, "Participação não encontrada");
        }
        output(200, "Participação encontrada com sucesso", $participacao);
    }


    public function listarTodasParticipacoes($parametros)
    { 
        $informacoesToken = validarToken();
        $participacoes = $this->participacao->listarTodas();
        output(200, "Lista de participações encontrada com sucesso", $participacoes);
    }

    public function deletarParticipacao($parametros)
    { 
        $informacoesToken = validarToken();
        $participacao = $this->participacao->delete($informacoesToken["id"], $parametros["id"]);
        if (!$participacao) {
            outputError(500, "Erro interno do servidor");
        }
        output(200, "Participação removida com sucesso", "OK", $participacao);
    }

    public function verificarParticipacao($parametros)
    { 
        $informacoesToken = validarToken();
        $participacao = $this->participacao->exists($parametros["idUsuario"], $parametros["idEvento"]);
        if (!$participacao) {
            outputError(500, "Erro interno do servidor");
        }
        output(200, "Participação encontrada com sucesso", "OK", $participacao);
    }
}

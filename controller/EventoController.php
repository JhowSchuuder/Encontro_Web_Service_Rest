<?php
    
    class EventoController{
        private $evento;
    
        public function __construct($evento) {
            $this->evento = $evento;
        }

        public function cadastrarEvento($parametros) {
            $informacoesToken = validarToken();

            $parametrosPost = parametrosJson() + parametrosPost();
            if(!verificarParametrosObrigatorios($parametrosPost, ["nome", "sala", "data_evento"]));
            $nomeEvento = $this->evento->listarNomeEvento($parametrosPost["nome"]);
            if($nomeEvento) outputError(404, "Evento ja existe");
            $evento = $this->evento->cadastrar($parametrosPost["nome"], $parametrosPost["sala"], $parametrosPost["data_evento"], $informacoesToken["id"]);
            if(!$evento) outputError(500, "Erro interno do servidor");
            output(200, "Evento cadastrado com sucesso", "OK", $evento);
        }
        
        public function buscarEvento($parametros) {
            $informacoesToken = validarToken();

            if(!verificarParametrosObrigatorios($parametros, ["id"]));

            $evento = $this->evento->listar($parametros["id"]);
            if(!$evento) outputError(404, "Evento não encontrado");
            output(200, "Evento encontrado com sucesso", $evento);
        }
        
        public function exibirTodos($parametros){

            validarToken();

            $eventos = $this->evento->listarTodos();
            if(!$eventos) outputError(404, "Evento não encontrado");
            output(200, "Eventos encontrados com sucesso!", $eventos);

        }

        public function deletarEvento($parametros){

            validarToken();

            if(!verificarParametrosObrigatorios($parametros, ["id"]));
            $evento = $this->evento->delete($parametros["id"]);
            if(!$evento) outputError(404, "Evento não encontrado");
            output(200, "Evento deletado com sucesso", $evento);
        }

        public function atualizarEvento($parametros){

            $tokenValidado = validarToken();
            
            $parametrosBody = parametrosJson() + parametrosPut();
            $data_evento = strtotime($parametrosBody["data_evento"]);
            $data_eventoFormatado = date('Y-m-d H:i:s', $data_evento);
            if(!verificarParametrosObrigatorios($parametrosBody,["nome","sala","data_evento"]));
            if(!verificarParametrosObrigatorios($parametros,["id"]));
            $eventoAntigo = $this->evento->listarEventoUsuario($parametros["id"], $tokenValidado["id"]);
            if ($eventoAntigo && $eventoAntigo["nome"] == $parametrosBody["nome"] && $eventoAntigo["sala"] == $parametrosBody["sala"] && $eventoAntigo["data_evento"] == $data_eventoFormatado) {
                outputError(400, "Nenhum dado foi alterado");
            }
            $evento = $this->evento->update($parametros["id"], $parametrosBody["nome"], $parametrosBody["sala"], $data_eventoFormatado);
            if(!$evento) outputError(404, "Evento não encontrado");
            output(200, "Evento atualizado com sucesso", $evento);
        }
    }

?>
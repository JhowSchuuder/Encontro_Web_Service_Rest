<?php

require_once("./config/header.php");
require_once("./config/utils.php");
require_once("./config/Token.php");

require_once("./models/Evento.php");
require_once("./models/Usuario.php");
require_once("./models/Participacao.php");

class Rotas {
    
    private $classes;
    private $rotas;

    public function __construct($rotas, $classes) {
        $this->classes = $classes;
        $this->rotas = $rotas;
    }

    public function getNomesParametros($rota) {
        $pattern = '#^' . preg_replace('/:([\w-]+)/', '(.*)', $rota) . '$#';
        preg_match($pattern, $rota, $matches);
        $nomesParametros = [];
        for ($i = 1; $i < count($matches); $i++) {
            $nomesParametros[] = str_replace(':', '', $matches[$i]);
        }
    
        return $nomesParametros;
    }

    public function verificarRota($url) {
        $parametros = [];
        $metodo = $_SERVER['REQUEST_METHOD'];
        foreach ($this->rotas as $rota => $conteudoRota) {
            $pattern = "#^" . preg_replace("/:([\w-]+)/", "([\\w-]+)", $rota) . "$#";
            preg_match($pattern, $url, $matches);
            
            if ($matches) {
                if(count($matches) > 1) {
                    $nomesParametros = $this->getNomesParametros($rota);
                    for($x=1; $x<count($matches); $x++) {
                        $parametros[$nomesParametros[$x-1]] = $matches[$x];
                    }
                    return [$rota, $parametros, $metodo];
                }
                return [$rota, $parametros, $metodo];
            }
        }
        outputError(404, "Rota nao encontrada");
    }
    
    public function chamarAcao($rota, $parametros, $metodo) {
        $entidade = explode('/', $rota)[0];
        $nomeArquivoController = $this->classes[$entidade]["controller"] . ".php";
        $nomeArquivoClasse = $this->classes[$entidade]["classe"] . ".php";
        
        if (!isset($this->rotas[$rota])) {
            outputError(404, "Rota não encontrada");
            return;
        }
        
        if (!isset($this->rotas[$rota]["rotas"][$metodo])) {
            outputError(405, "Metodo nao permitido para essa rota");
            return;
        }
    
        $nomeArquivoController = $this->classes[$entidade]["controller"] . ".php";
        if (!file_exists("./controller/{$nomeArquivoController}")) {
            outputError(500, "Erro interno do servidor");
            return;
        }
    
        $nomeArquivoClasse = $this->classes[$entidade]["classe"] . ".php";
        if (!file_exists("./models/{$nomeArquivoClasse}")) {
            outputError(500, "Erro interno do servidor");
            return;
        }
        try {

            require_once("./controller/{$nomeArquivoController}");
            require_once("./models/{$nomeArquivoClasse}");

            $acao = $this->rotas[$rota]["rotas"][$metodo];
            $classe = new $this->classes[$entidade]["classe"]();
            $controller = new $this->classes[$entidade]["controller"]($classe);
            $controller->$acao($parametros);
        } catch (Exception $e) {
            outputError(500, $e->getMessage());
        }
    }
}

$classes = [
    "usuario" => [
        "classe" => "Usuario",
        "controller" => "UsuarioController",
    ],
    "evento" => [
        "classe" => "Evento",
        "controller" => "EventoController",
    ],
    "participacao" => [
        "classe" => "Participacao",
        "controller" => "ParticipacaoController",
    ],
];

$rotas = [
    "usuario" => [
        "rotas" => [
            "POST" => "cadastrar",
            "PUT" => "editar"
        ],
    ],
    "usuario/login" => [
        "rotas" => [
            "POST" => "login"
        ]
    ],
    "usuario/logout" => [
        "rotas" => [
            "POST" => "logout"
        ]
    ],
    "usuario/perfil" => [
        "rotas" => [
            "GET" => "perfil",
        ]
    ],
    "evento" => [
        "rotas" => [
            "POST" => "cadastrarEvento",
            "GET" => "exibirTodos"
        ]
    ],
    "evento/:id" => [
        "rotas" => [
            "GET" => "buscarEvento",
            "PUT" => "atualizarEvento",
            "DELETE" => "deletarEvento"
        ]
    ],
    "participacao" => [
        "rotas" => [
            "POST" => "cadastrarParticipacao",
            "GET" => "buscarPorIdUsuario",
        ]
    ],
    "participacao/:id" => [
        "rotas" => [
            "DELETE" => "deletarParticipacao"
        ]
    ],
];

$rotasApp = new Rotas($rotas, $classes);

$uri = $_GET['uri'] ?? "";
$metodo = $_SERVER['REQUEST_METHOD'];

try {
    list($rota, $parametros, $metodo) = $rotasApp->verificarRota($uri);
    
    $rotasApp->chamarAcao($rota, $parametros, $metodo);
} catch (Exception $e) {
    outputError(500, $e->getMessage());
}

?>
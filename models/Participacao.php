<?php
require_once(__DIR__ . "/../config/Conexao.php");

class Participacao
{
    public static function cadastrar($idUsuario, $idEvento, $data_inscricao)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO participacao(idUsuario, idEvento, data_inscricao) VALUES (?, ?, ?)");
            $stmt->execute([$idUsuario, $idEvento, $data_inscricao]);

            $linhasAlteradas = $stmt->rowCount();
            if ($linhasAlteradas > 0) {
                $usuario = self::listarEventoUsuario($idEvento, $idUsuario);
                return $usuario;
            } else {
                return null;
            }
            //code...
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function listar($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT participacao.idUsuario, participacao.idEvento, participacao.data_inscricao FROM participacao WHERE idUsuario = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetchAll();

            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function listarEventoUsuario($idEvento, $idUsuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT participacao.idUsuario, participacao.idEvento, participacao.data_inscricao FROM participacao WHERE idUsuario = ? AND idEvento = ?");
            $stmt->execute([$idUsuario, $idEvento]);
            $resultado = $stmt->fetchAll();

            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function listarTodas()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT participacao.idUsuario, participacao.idEvento, participacao.data_inscricao FROM participacao");
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function exists($idUsuario, $idEvento)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT participacao.idUsuario, participacao.idEvento, participacao.data_inscricao FROM participacao WHERE idUsuario = ? AND idEvento = ?");
            $stmt->execute([$idUsuario, $idEvento]);
            $resultado = $stmt->fetchAll();

            if (count($resultado) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function delete($idUsuario, $idEvento)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM participacao WHERE idUsuario = ? AND idEvento = ?");
            $stmt->execute([$idUsuario, $idEvento]);

            $linhasAlteradas = $stmt->rowCount();
            if ($linhasAlteradas > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }
}

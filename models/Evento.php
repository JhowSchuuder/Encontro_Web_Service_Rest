<?php

require_once(__DIR__ . "/../config/Conexao.php");

class Evento{

    public static function cadastrar($nome, $sala, $data_evento, $idUsuario){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao ->prepare("INSERT INTO evento(nome, sala, data_evento, idUsuario) VALUES (?, ? , ?, ?)");
            $stmt->execute([$nome, $sala, $data_evento, $idUsuario]);
            $linhas_alteradas = $stmt->rowCount();
            if($linhas_alteradas > 0){
                $idEvento = $conexao->lastInsertId();
                $evento = self::listar($idEvento);
                return $evento;
            }else{
                return null;
            }
        } catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }

public static function listar($idEvento){
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT evento.nome, evento.sala, evento.data_evento FROM evento WHERE evento.id = ?");
        $stmt->execute([$idEvento]);
        $resultado = $stmt->fetchAll();
        if (count($resultado) > 0){
            return $resultado[0];
        }else{
            return null;
        }
    }catch(PDOException $e){
        echo $e->getMessage();
        return null;
    }
}

public static function listarEventoUsuario($idEvento, $idUsuario){
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT evento.nome, evento.sala, evento.data_evento FROM evento WHERE evento.id = ? and evento.idUsuario = ?");
        $stmt->execute([$idEvento, $idUsuario]);
        $resultado = $stmt->fetchAll();
        if (count($resultado) > 0){
            return $resultado[0];
        }else{
            return null;
        }
    }catch(PDOException $e){
        echo $e->getMessage();
        return null;
    }
}

public static function listarNomeEvento($nome) {
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT e.nome FROM evento e WHERE e.nome = ?");
        $stmt->execute([$nome]);
        $resultado = $stmt->fetchAll();

        if(count($resultado) > 0) {
            return $resultado;
        } else {
            return null;
        }
        
    } catch (Exception $e) {
        echo $e->getMessage();
        die;
    }
}

public static function listarTodos(){
    try{
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT evento.nome, evento.sala, evento.data_evento FROM evento");
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        if (count($resultado) > 0){
            return $resultado;
        }else{
            return null;
        }
    }catch(PDOException $e){
        echo $e->getMessage();
        return null;
    }

}

public static function exists ($idEvento){
    try{
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT evento.nome, evento.sala, evento.data_evento FROM evento WHERE evento.id = ?");
        $stmt->execute([$idEvento]);
        $resultado = $stmt->fetchAll();
        if (count($resultado) > 0){
            return true;
        }else{
            return false;
        }
    }catch(PDOException $e){
        echo $e->getMessage();
        return false;
    }

}

    public static function delete($id) {
        try {
            $conexao = Conexao::getConexao();

            // Remover as participações relacionadas ao evento
            $stmtParticipacao = $conexao->prepare("DELETE FROM participacao WHERE idEvento = ?");
            $stmtParticipacao->execute([$id]);
            
            $stmt = $conexao->prepare("DELETE FROM evento WHERE id = ?");
            $stmt->execute([$id]);

            $linhasAlteradas = $stmt->rowCount();
            if($linhasAlteradas > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function update($id,$nome,$sala,$data_evento){
        try{
            
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE evento SET nome=?, sala=?, data_evento=? WHERE id=?");
            $stmt->execute([$nome, $sala, $data_evento, $id]);

            $linhasAlteradas = $stmt->rowCount();
            if($linhasAlteradas > 0) {
                return self::listar($id);
            } else {
                return false;
            }
        }catch(Exception $e){
        }
    }
}

?>
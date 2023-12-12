<?php

require_once(__DIR__ . "/../config/Conexao.php");

class Usuario {
    
    public static function cadastrar($nome, $username, $senha, $datanasc) {
        try{
            $senhaCripto = password_hash($senha, PASSWORD_BCRYPT);
            $conexao = Conexao::getConexao();
            $stmt = $conexao ->prepare("INSERT INTO usuario(nome, username, senha, datanasc) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $username, $senhaCripto, $datanasc]);
            
            $linhasAlteradas = $stmt->rowCount();
            if($linhasAlteradas > 0){
               $idUsuario = $conexao->lastInsertId();
               $usuario = self::listar($idUsuario);
               return $usuario;
            }
            else{
                return null;
            }
        }catch(Exception $e){
            echo $e->getMessage();
            die;
        }
    }

    public static function listar($id) {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT usuario.username, usuario.datanasc, usuario.nome FROM usuario WHERE id = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetchAll();

            if(count($resultado) > 0) {
                return $resultado[0];
            } else {
                return null;
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function listarCompleto($id) {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT usuario.username, usuario.senha, usuario.datanasc, usuario.nome FROM usuario WHERE id = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetchAll();

            if(count($resultado) > 0) {
                return $resultado[0];
            } else {
                return null;
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function listarUsername($username) {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT u.username FROM usuario u WHERE u.username = ?");
            $stmt->execute([$username]);
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

    public function dadosUsuarioUsernameSenha($username, $senha) {
        $resultado = [];
        try {
            $conexao = Conexao::getConexao(); 
            $stmt = $conexao->prepare("SELECT usuario.id, usuario.username, usuario.senha, usuario.datanasc FROM usuario WHERE username=?");
            $stmt->execute([$username]);
            $usuarios = $stmt->fetchAll();
            if(count($usuarios) > 0) {
                $resultado = $usuarios[0];
                if(password_verify($senha, $resultado["senha"])) {
                    return $resultado;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch(Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function exists($id) {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE id = ?");
            $stmt->execute([$id]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public static function delete($id) {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM usuario WHERE id = ?");
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

    public static function update($nome,$datanasc, $senha, $id){
        try{
            $senhaCripto = password_hash($senha, PASSWORD_BCRYPT);
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE usuario SET nome=?, datanasc=?, senha=? WHERE id=?");
            $stmt->execute([$nome,$datanasc, $senhaCripto, $id]);

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
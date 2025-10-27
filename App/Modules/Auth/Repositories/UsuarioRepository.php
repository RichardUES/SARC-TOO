<?php

namespace App\Modules\Auth\Repositories;

use App\Config\Database;
use App\Models\Usuario;
use App\Modules\Auth\Repositories\interfaces\IRol;
use App\Modules\Auth\Repositories\interfaces\IUsuario;
use App\Modules\Auth\RolRepository;
use App\Models\enums\Status;
use PDO;
use PDOException;
use DateTime;

class UsuarioRepository implements IUsuario
{

  private IRol $rolRepository;
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getIntance()->getConnection();
    // $this->rolRepository = new RolRepository();
  }

  /**
   * Guarda un Usuario (insert o update).
   * Retorna el Usuario con su codigo o null en error.
   */
  public function save(Usuario $user): ?Usuario
  {
    try {
      $this->db->beginTransaction();

      if (isset($user->codigo)) {
        // UPDATE
        $query = "UPDATE USUARIOS SET USU_ROL_ID = :ROL_ID, USU_AGENCIA_ID = :AGENCIA_ID, USU_USERNAME = :USERNAME, USU_EMAIL = :EMAIL, USU_CLAVE = :CLAVE WHERE USU_CODIGO = :CODIGO";

        $cod = $user->codigo;
        $rolID = $user->rolID;
        $agenciaID = $user->agenciaID;
        $username = $user->username;
        $email = $user->email;
        $clave = $user->clave;

        $ps = $this->db->prepare($query);
        $ps->bindParam(":ROL_ID", $rolID);
        $ps->bindParam(":AGENCIA_ID", $agenciaID);
        $ps->bindParam(":USERNAME", $username);
        $ps->bindParam(":EMAIL", $email);
        $ps->bindParam(":CLAVE", $clave);
        $ps->bindParam(":CODIGO", $cod);
        $ps->execute();

        $this->db->commit();
        return $user;
      }

      // INSERT
      $rolID = $user->rolID;
      $agenciaID = $user->agenciaID;
      $username = $user->username;
      $email = $user->email;
      $clave = $user->clave; // hasheada

      $query = "INSERT INTO USUARIOS(USU_ROL_ID, USU_AGENCIA_ID, USU_USERNAME, USU_EMAIL, USU_CLAVE) 
            VALUES(:ROL_ID, :AGENCIA_ID, :USERNAME, :EMAIL, :CLAVE)";

      $ps = $this->db->prepare($query);
      $ps->bindParam(":ROL_ID", $rolID);
      $ps->bindParam(":AGENCIA_ID", $agenciaID);
      $ps->bindParam(":USERNAME", $username);
      $ps->bindParam(":EMAIL", $email);
      $ps->bindParam(":CLAVE", $clave);
      $ps->execute();

      $lastId = $this->db->lastInsertId();
      $user->setCodigo($lastId);

      $this->db->commit();
      return $user;

    } catch (PDOException $ex) {
      if ($this->db->inTransaction()) $this->db->rollBack();
      return null;
    }
  }

  public function delete(int $id): bool
  {
    // TODO: Implement delete() method.
    return false;
  }

  public function findAll(): array
  {
    try {
      $query = "SELECT * FROM USUARIOS U WHERE U.USU_ESTADO = 'ACTIVO' ORDER BY U.USU_CODIGO ASC";
      $ps = $this->db->prepare($query);
      $ps->execute();

      $usuarios_arr = $ps->fetchAll(PDO::FETCH_ASSOC);
      $usuarios_list = [];

      foreach ($usuarios_arr as $usuario) {
        $user = $this->getUsuario($usuario);
        $usuarios_list[] = $user;
      }

      return $usuarios_list;
    } catch (PDOException $ex) {
      return [];
    }
  }

  public function findById(int $id): Usuario | null
  {
    try {
      $query = "SELECT * FROM USUARIOS U WHERE U.USU_CODIGO = :USUARIO_ID";
      $ps = $this->db->prepare($query);
      $ps->bindParam(':USUARIO_ID', $id);
      $ps->execute();

      $usuario = $ps->fetch(PDO::FETCH_ASSOC);
      if (!$usuario) return null;

      return $this->getUsuario($usuario);
    } catch (PDOException $e) {
      return null;
    }
  }

  public function signin(string $userOrEmail, string $password): mixed
  {
    $query = "SELECT u.USU_CODIGO AS CODIGO, 
                  u.USU_ROL_ID AS ROL_ID, 
                  u.USU_AGENCIA_ID AS AGENCIA_ID, 
                  u.USU_USERNAME AS USERNAME, 
                  u.USU_EMAIL AS EMAIL,
                  u.USU_CLAVE AS CLAVE,
                  u.USU_ESTADO AS ESTADO,
                  u.USU_FECHA_REGISTRO AS FECHA_REGISTRO,
                  u.USU_FUM AS FUM
              FROM usuarios u 
              WHERE u.USU_USERNAME = :email_username OR u.USU_EMAIL = :email_username";

    try {
      $ps = $this->db->prepare($query);
      $ps->bindParam(':email_username', $userOrEmail);
      $ps->execute();

      if ($ps->rowCount() == 1) {
        $data_usuario = $ps->fetch(PDO::FETCH_OBJ);

        $usuario = new Usuario();
        $usuario->setCodigo($data_usuario->CODIGO);
        $usuario->setRolID($data_usuario->ROL_ID);
        $usuario->setAgenciaID($data_usuario->AGENCIA_ID ?? 0);
        $usuario->setUsername($data_usuario->USERNAME);
        $usuario->setEmail($data_usuario->EMAIL);
        $usuario->setClave($data_usuario->CLAVE);
        if (!empty($data_usuario->FECHA_REGISTRO)) {
          $usuario->setFechaRegistro(new DateTime($data_usuario->FECHA_REGISTRO));
        }
        if (!empty($data_usuario->FUM)) {
          $usuario->setFum(new DateTime($data_usuario->FUM));
        }

        $usuario->setEstado($data_usuario->ESTADO);
        

        $verify = password_verify($password, $usuario->clave);
        if ($verify) return $usuario;
        return false;
      }

      return 'Sin Registros';
    } catch (PDOException $e) {
      return false;
    }
  }// Fin de metodo signin()

  private function getUsuario($usuario): Usuario
  {
    $usuario_obj = new Usuario();
    $usuario_obj->setCodigo($usuario['USU_CODIGO']);
    $usuario_obj->setRolID($usuario['USU_ROL_ID']);
    $usuario_obj->setUsername($usuario['USU_USERNAME']);
    $usuario_obj->setEmail($usuario['USU_EMAIL']);
    $usuario_obj->setClave($usuario['USU_CLAVE']);
    $usuario_obj->setFechaRegistro($usuario['USU_FECHA_REGISTRO']);
    $usuario_obj->setFum($usuario['USU_FUM']);
    $usuario_obj->setEstado($usuario['USU_ESTADO']);

    return $usuario_obj;
  
  }// FIN de metodo getUsuario()

}// FIN DE CLASE


<?php

namespace App\Modules\Auth\Repositories;

use App\Config\Database;
use App\Models\enums\RolType;
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
   * 
   * Con AUTOCOMMIT habilitado, no necesita transacciones explícitas.
   * Si se requiere atomicidad con otras operaciones, debe manejarse desde el servicio.
   * 
   * @param Usuario $user Usuario a guardar
   * @return Usuario|null Retorna el Usuario con su codigo o null en error
   */
  public function save(Usuario $user): ?Usuario
  {
    try {
      if (isset($user->codigo)) {
        // UPDATE
        $query = "UPDATE USUARIOS 
          SET USU_ROL_ID = :ROL_ID,
              USU_AGENCIA_ID = :AGENCIA_ID,
              USU_USERNAME = :USERNAME,
              USU_EMAIL = :EMAIL,
              USU_CLAVE = :CLAVE,
              USU_FUM = :FUM,
              USU_OCUPADO = :OCUPADO
          WHERE USU_CODIGO = :CODIGO";

        $cod = $user->codigo;
        $rolID = $user->rolID;
        $agenciaID = $user->agenciaID;
        $username = $user->username;
        $email = $user->email;
        $clave = $user->clave;
        $fum = $user->fum;
        $ocupado = $user->ocupado;

        $ps = $this->db->prepare($query);
        $ps->bindParam(":ROL_ID", $rolID);
        $ps->bindParam(":AGENCIA_ID", $agenciaID);
        $ps->bindParam(":USERNAME", $username);
        $ps->bindParam(":EMAIL", $email);
        $ps->bindParam(":CLAVE", $clave);
        $ps->bindParam(":FUM", $fum);
        $ps->bindParam(":OCUPADO", $ocupado);
        $ps->bindParam(":CODIGO", $cod);
        $ps->execute();

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

      return $user;

    } catch (PDOException $ex) {
      error_log("UsuarioRepository::save - Error: " . $ex->getMessage());
      return null;
    }
  }

  public function updateDisponibilidadAgente(Usuario $userAgent): bool{

    try {

      $ocupado = $userAgent->ocupado;
      $codigo = $userAgent->codigo;

      $query = "UPDATE USUARIOS 
          SET USU_OCUPADO = :OCUPADO, USU_FUM = NOW()
          WHERE USU_CODIGO = :CODIGO";

      $ps = $this->db->prepare($query);
      $ps->bindParam(":OCUPADO", $ocupado);
      $ps->bindParam(":CODIGO", $codigo);
      $ps->execute();

      return true;

    } catch (PDOException $ex) {
  
      return false;
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

  public function findById(int $id): Usuario|null
  {
    try {
      $query = "SELECT * FROM USUARIOS U WHERE U.USU_CODIGO = :USUARIO_ID";
      $ps = $this->db->prepare($query);
      $ps->bindParam(':USUARIO_ID', $id);
      $ps->execute();

      $usuario = $ps->fetch(PDO::FETCH_ASSOC);
      if (!$usuario)
        return null;

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
        if ($verify)
          return $usuario;
        return false;
      }

      return 'Sin Registros';
    } catch (PDOException $e) {
      return false;
    }
  }// Fin de metodo signin()

  /**
   * Metodo que busca si un agente esta ocupado
   * @param int $agentID El código del agente a verificar
   * @return bool|null true si está ocupado (USU_OCUPADO = 'S'), false si está libre, null en error
   */
  public function isAgenteOcupado(int $agentID): ?bool
  {
    try {
      $rolType = RolType::AGENT->value;
      
      /* la consulta, hace la pregunta ¿Esta libre? "S" es (Si, esta ocupado), 
        entonces devuelve 1, es decir true
        Si esta libre, devuelve 0, es decir false ("N")
      */
      $query = "SELECT
                  EXISTS(
                      SELECT 1
                      FROM usuarios u
                      WHERE u.USU_ESTADO = 'ACTIVO'
                        AND u.USU_ROL_ID = :ROL_ID
                        AND u.USU_OCUPADO = 'S'
                        AND u.USU_CODIGO = :AGENTE_ID
                  ) AS esta_ocupado";

      $ps = $this->db->prepare($query);
      $ps->bindValue(':ROL_ID', $rolType, PDO::PARAM_INT);
      $ps->bindValue(':AGENTE_ID', $agentID, PDO::PARAM_INT);
      $ps->execute();
      
      $isOcupado = (bool) $ps->fetchColumn();

      return $isOcupado;

    } catch (PDOException $th) {
      echo "Error al verificar si el agente está ocupado: " . $th->getMessage();
      error_log("Error al verificar si el agente está ocupado: " . $th->getMessage());
      return null;
    }
  }

  /**
   * Obtener lista completa de agentes activos para reportes
   * @return array Lista de agentes con información básica
   */
  public function obtenerListaAgentes(): array
  {
    try {
      $rolType = RolType::AGENT->value;

      $query = "SELECT 
                  USU_CODIGO,
                  USU_USERNAME,
                  USU_EMAIL,
                  USU_AGENCIA_ID,
                  USU_ESTADO,
                  USU_OCUPADO,
                  USU_FECHA_REGISTRO
                FROM USUARIOS
                WHERE USU_ESTADO = 'ACTIVO'
                  AND USU_ROL_ID = :ROL_ID
                ORDER BY USU_USERNAME ASC";

      $ps = $this->db->prepare($query);
      $ps->bindValue(':ROL_ID', $rolType, PDO::PARAM_INT);
      $ps->execute();
      
      return $ps->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $ex) {
      error_log("Error al obtener lista de agentes: " . $ex->getMessage());
      return [];
    }
  }

  /**
   * Obtener el primer agente disponible (no ocupado)
   * @return array|null Devuelve el array del agente disponible o null si no hay ninguno
   */
  public function obtenerAgenteDisponible(): ?array
  {
    try {
      $rolType = RolType::AGENT->value;

      // 1️⃣ Buscamos todos los agentes activos con rol de agente
      $query = "SELECT *
                FROM usuarios
                WHERE USU_ESTADO = 'ACTIVO'
                  AND USU_ROL_ID = :ROL_ID
                ORDER BY USU_FUM ASC"; // Ordenar por última actualización para distribuir carga

      $ps = $this->db->prepare($query);
      $ps->bindValue(':ROL_ID', $rolType, PDO::PARAM_INT);
      $ps->execute();
      $agents = $ps->fetchAll(PDO::FETCH_ASSOC);

      // 2️⃣ Buscamos el primero que esté libre (USU_OCUPADO = 'N' o isAgenteOcupado = false)
      foreach ($agents as $agente) {
        $isOcupado = $this->isAgenteOcupado((int) $agente['USU_CODIGO']);
        
        // Si isAgenteOcupado devuelve null (error), saltamos este agente
        if ($isOcupado === null) {
          continue;
        }
        
        // Si NO está ocupado (isOcupado = false), lo devolvemos
        if (!$isOcupado) {
          return $agente; // ✅ Devuelve el primer agente libre encontrado
        }
      }

      // 3️⃣ Si llegamos aquí, no hay ningún agente disponible
      return null;

    } catch (PDOException $ex) {
      error_log("Error al obtener agente disponible: " . $ex->getMessage());
      return null;
    }

  }

  private function getUsuario($usuario): Usuario
  {
    $usuario_obj = new Usuario();
    $usuario_obj->setCodigo($usuario['USU_CODIGO']);
    $usuario_obj->setRolID($usuario['USU_ROL_ID']);
    $usuario_obj->setUsername($usuario['USU_USERNAME']);
    $usuario_obj->setEmail($usuario['USU_EMAIL']);
    $usuario_obj->setClave($usuario['USU_CLAVE']);
    
    // Convertir fechas string a DateTime si no están vacías
    if (!empty($usuario['USU_FECHA_REGISTRO'])) {
      $usuario_obj->setFechaRegistro(new DateTime($usuario['USU_FECHA_REGISTRO']));
    }
    
    if (!empty($usuario['USU_FUM'])) {
      $usuario_obj->setFum(new DateTime($usuario['USU_FUM']));
    }
    
    $usuario_obj->setEstado($usuario['USU_ESTADO']);
    $usuario_obj->setOcupado($usuario['USU_OCUPADO']);

    return $usuario_obj;

  }// FIN de metodo getUsuario()

}// FIN DE CLASE


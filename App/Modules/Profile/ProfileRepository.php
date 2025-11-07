<?php

namespace App\Modules\Profile;

use App\Config\Database;
use App\Models\Cliente;
use PDO;
use PDOException;

class ProfileRepository
{

  private PDO $db;

  public function __construct()
  {

    $this->db = Database::getIntance()->getConnection();

  }

  /**
   * Crea un nuevo perfil de cliente o actualiza uno existente.
   * 
   * Si el cliente tiene un código asignado, realiza un UPDATE.
   * Si no tiene código, realiza un INSERT y asigna el ID generado.
   * 
   * IMPORTANTE: Este método NO maneja transacciones. La transacción debe ser
   * manejada por la capa superior (servicio).
   * 
   * @param Cliente $client Objeto cliente a crear o actualizar
   * @return Cliente|null Retorna el cliente con su código asignado si fue exitoso, null si hubo error
   */
  public function createProfileClient(Cliente $client): ?Cliente
  {
    try {
      if (isset($client->codigo)) {
        // UPDATE
        $cod = $client->codigo;
        $usuarioID = $client->usuarioID;
        $fecha_nac = $client->fecha_nac;
        $primer_nombre = $client->primer_nombre;
        $segundo_nombre = $client->segundo_nombre;
        $primer_apellido = $client->primer_apellido;
        $segundo_apellido = $client->segundo_apellido;
        $telefono = $client->telefono;
        $dui = $client->dui;

        $query = "UPDATE CLIENTES SET 
          CLI_USUARIO_ID = :USUARIO_ID,
          CLI_FECHA_NAC = :FECHA_NAC, 
          CLI_PRIMER_NOM = :PRIMER_NOMBRE,
          CLI_SEGUNDO_NOM = :SEGUNDO_NOMBRE, 
          CLI_PRIMER_APE = :PRIMER_APELLIDO, 
          CLI_SEGUNDO_APE = :SEGUNDO_APELLIDO, 
          CLI_TELEFONO = :TELEFONO, 
          CLI_DUI = :DUI 
          WHERE CLI_CODIGO = :CODIGO";

        $ps = $this->db->prepare($query);
        $ps->bindParam(":USUARIO_ID", $usuarioID);
        $birthDate = $fecha_nac->format('Y-m-d');
        $ps->bindParam(":FECHA_NAC", $birthDate);
        $ps->bindParam(":PRIMER_NOMBRE", $primer_nombre);
        $ps->bindParam(":SEGUNDO_NOMBRE", $segundo_nombre);
        $ps->bindParam(":PRIMER_APELLIDO", $primer_apellido);
        $ps->bindParam(":SEGUNDO_APELLIDO", $segundo_apellido);
        $ps->bindParam(":TELEFONO", $telefono);
        $ps->bindParam(":DUI", $dui);
        $ps->bindParam(":CODIGO", $cod);
        $ps->execute();

        return $client;
      }

      // INSERT
      $query = "INSERT INTO CLIENTES (CLI_USUARIO_ID, CLI_FECHA_NAC, CLI_PRIMER_NOM, CLI_SEGUNDO_NOM, CLI_PRIMER_APE, CLI_SEGUNDO_APE, CLI_TELEFONO, CLI_DUI)
            VALUES (:USUARIO_ID, :FECHA_NAC, :PRIMER_NOMBRE, :SEGUNDO_NOMBRE, :PRIMER_APELLIDO, :SEGUNDO_APELLIDO, :TELEFONO, :DUI)";

      $usuarioID = $client->usuarioID;
      $fecha_nac = $client->fecha_nac;
      $primer_nombre = $client->primer_nombre;
      $segundo_nombre = $client->segundo_nombre;
      $primer_apellido = $client->primer_apellido;
      $segundo_apellido = $client->segundo_apellido;
      $telefono = $client->telefono;
      $dui = $client->dui;

      $ps = $this->db->prepare($query);
      $ps->bindParam(":USUARIO_ID", $usuarioID);
      $birthDate = $fecha_nac->format('Y-m-d');
      $ps->bindParam(":FECHA_NAC", $birthDate);
      $ps->bindParam(":PRIMER_NOMBRE", $primer_nombre);
      $ps->bindParam(":SEGUNDO_NOMBRE", $segundo_nombre);
      $ps->bindParam(":PRIMER_APELLIDO", $primer_apellido);
      $ps->bindParam(":SEGUNDO_APELLIDO", $segundo_apellido);
      $ps->bindParam(":TELEFONO", $telefono);
      $ps->bindParam(":DUI", $dui);
      $ps->execute();

      $lastId = $this->db->lastInsertId();
      $client->setCodigo($lastId);

      return $client;

    } catch (PDOException $e) {
      error_log("ProfileRepository::createProfileClient - Error: " . $e->getMessage());
      throw $e; // Lanzamos la excepción para que el servicio haga rollback
    }
  }

  public function getProfileByUserId(int $userId): ?Cliente
  {

    try {

      $query = "SELECT * FROM CLIENTES WHERE CLI_USUARIO_ID = :USUARIO_ID";
      $ps = $this->db->prepare($query);
      $ps->bindParam(":USUARIO_ID", $userId);
      $ps->execute();

      $result = $ps->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        $client = new Cliente();
        $client->setCodigo($result['CLI_CODIGO']);
        $client->setUsuarioID($result['CLI_USUARIO_ID']);
        $client->setFechaNac(new \DateTime($result['CLI_FECHA_NAC']));
        $client->setPrimerNombre($result['CLI_PRIMER_NOM']);
        $client->setSegundoNombre($result['CLI_SEGUNDO_NOM']);
        $client->setPrimerApellido($result['CLI_PRIMER_APE']);
        $client->setSegundoApellido($result['CLI_SEGUNDO_APE']);
        $client->setTelefono($result['CLI_TELEFONO']);
        $client->setDui($result['CLI_DUI']);

        return $client;
      }

      return null;
    } catch (PDOException $e) {
      return null;
    }

  } // Fin de getProfileByUserId

  /**
   * Actualiza la contraseña de un usuario en la base de datos.
   * 
   * Modifica el campo USU_CLAVE con la nueva contraseña (debe venir hasheada)
   * y actualiza la fecha de última modificación (USU_FUM) automáticamente.
   * 
   * IMPORTANTE: Este método NO maneja transacciones. La transacción debe ser
   * manejada por la capa superior (servicio/controlador).
   * 
   * @param int $userID Código único del usuario
   * @param string $newPassword Nueva contraseña hasheada con password_hash()
   * @return bool true si la actualización fue exitosa, false en caso contrario
   * 
   * @throws PDOException Si ocurre un error en la base de datos
   */
  public function updateUserPassword(int $userID, string $newPassword): bool
  {
    try {
      $query = "UPDATE USUARIOS 
        SET USU_CLAVE = :NEW_PASSWORD, USU_FUM = NOW()
        WHERE USU_CODIGO = :USER_ID";

      $ps = $this->db->prepare($query);
      $ps->bindParam(":NEW_PASSWORD", $newPassword, PDO::PARAM_STR);
      $ps->bindParam(":USER_ID", $userID, PDO::PARAM_INT);
      $ps->execute();
      
      $isOk = $ps->rowCount() > 0;
      
      if ($isOk) {
        error_log("✅ ProfileRepository::updateUserPassword - Contraseña actualizada para usuario ID: {$userID}");
      } else {
        error_log("⚠️ ProfileRepository::updateUserPassword - No se encontró el usuario ID: {$userID}");
      }

      return $isOk;

    } catch (PDOException $e) {
      
      error_log("❌ ProfileRepository::updateUserPassword - Error: " . $e->getMessage());
      throw $e; // Lanzamos la excepción para que el servicio haga rollback
    }
  }

  public function validateUserPassword(int $userID, string $currentPassword): bool
  {
    try {
      $query = "SELECT USU_CLAVE FROM USUARIOS WHERE USU_CODIGO = :USER_ID";
      $ps = $this->db->prepare($query);
      $ps->bindParam(":USER_ID", $userID);
      $ps->execute();

      $result = $ps->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        $storedPassword = $result['USU_CLAVE'];
        return password_verify($currentPassword, $storedPassword);
      }

      return false;

    } catch (PDOException $e) {
      return false;
    }
  }

}//  Fin de clase
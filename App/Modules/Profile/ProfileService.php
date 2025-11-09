<?php

namespace App\Modules\Profile;

use App\Config\Database;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Rol;
use PDO;
use PDOException;

class ProfileService
{

  private ProfileRepository $profileRepository;
  private PDO $db;

  public function __construct() {
    $this->profileRepository = new ProfileRepository();
    $this->db = Database::getIntance()->getConnection();
  }

  /**
   * Crea o actualiza un perfil de cliente.
   * 
   * Maneja la transacción completa para garantizar la integridad de la operación.
   * 
   * @param Cliente $client Objeto cliente a crear o actualizar
   * @return Cliente|null Retorna el cliente si fue exitoso, null en caso contrario
   */
  public function createProfileClient(Cliente $client): ?Cliente
  {
    try {
      return $this->profileRepository->createProfileClient($client);
    } catch (PDOException $e) {
      error_log("ProfileService::createProfileClient - Error: " . $e->getMessage());
      return null;
    }
  }

  public function updateProfileClient(Cliente $client): void
  {

  }

  public function getProfileByUserID(int $userID): ?Cliente
  {
    return $this->profileRepository->getProfileByUserID($userID);
  }

  /**
   * Actualiza la contraseña de un usuario.
   * 
   * Maneja la transacción completa para garantizar la integridad de la operación.
   * La contraseña debe venir ya hasheada con password_hash().
   * 
   * @param int $userID Código único del usuario
   * @param string $newPassword Nueva contraseña hasheada
   * @return bool true si la actualización fue exitosa, false en caso contrario
   */
  public function updateUserPassword(int $userID, string $newPassword): bool
  {
    try {
      return $this->profileRepository->updateUserPassword($userID, $newPassword);
    } catch (PDOException $e) {
      error_log("ProfileService::updateUserPassword - Error: " . $e->getMessage());
      return false;
    }
  }

  public function validateUserPassword(int $userID, string $currentPassword): bool
  {
    return $this->profileRepository->validateUserPassword($userID, $currentPassword);
  }

  /**
   * Verifica si un nombre de usuario ya existe
   * 
   * @param string $username Nombre de usuario a verificar
   * @return bool true si existe, false si no existe
   */
  public function usernameExists(string $username): bool
  {
    try {
      $sql = "SELECT COUNT(*) FROM USUARIOS WHERE USU_USERNAME = :username";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      
      return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
      error_log("ProfileService::usernameExists - Error: " . $e->getMessage());
      return true; // Retornar true para prevenir duplicados en caso de error
    }
  }

  /**
   * Verifica si un email ya existe
   * 
   * @param string $email Email a verificar
   * @return bool true si existe, false si no existe
   */
  public function emailExists(string $email): bool
  {
    try {
      $sql = "SELECT COUNT(*) FROM USUARIOS WHERE USU_EMAIL = :email";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      
      return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
      error_log("ProfileService::emailExists - Error: " . $e->getMessage());
      return true; // Retornar true para prevenir duplicados en caso de error
    }
  }

  /**
   * Registra un cliente completo usando el stored procedure SP_REGISTRO_CLIENTE
   * 
   * @param int $rolID ID del rol (siempre 4 para cliente)
   * @param string $username Nombre de usuario
   * @param string $email Email del usuario
   * @param string $passwordHash Contraseña hasheada
   * @param string $fechaNacimiento Fecha de nacimiento (Y-m-d)
   * @param string $primerNombre Primer nombre
   * @param string $segundoNombre Segundo nombre (puede ser vacío)
   * @param string $primerApellido Primer apellido
   * @param string $segundoApellido Segundo apellido (puede ser vacío)
   * @param string $telefono Teléfono (solo números)
   * @param string $dui DUI (solo números)
   * @return bool true si fue exitoso, false en caso contrario
   */
  public function registrarClienteCompleto(
    int $rolID, 
    string $username, 
    string $email, 
    string $passwordHash,
    string $fechaNacimiento,
    string $primerNombre,
    string $segundoNombre,
    string $primerApellido,
    string $segundoApellido,
    string $telefono,
    string $dui
  ): bool {
    try {
      $this->db->beginTransaction();

      $sql = "CALL SP_REGISTRO_CLIENTE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      
      $stmt->bindParam(1, $rolID, PDO::PARAM_INT);
      $stmt->bindParam(2, $username, PDO::PARAM_STR);
      $stmt->bindParam(3, $email, PDO::PARAM_STR);
      $stmt->bindParam(4, $passwordHash, PDO::PARAM_STR);
      $stmt->bindParam(5, $fechaNacimiento, PDO::PARAM_STR);
      $stmt->bindParam(6, $primerNombre, PDO::PARAM_STR);
      $stmt->bindParam(7, $segundoNombre, PDO::PARAM_STR);
      $stmt->bindParam(8, $primerApellido, PDO::PARAM_STR);
      $stmt->bindParam(9, $segundoApellido, PDO::PARAM_STR);
      $stmt->bindParam(10, $telefono, PDO::PARAM_STR);
      $stmt->bindParam(11, $dui, PDO::PARAM_STR);

      $resultado = $stmt->execute();

      if ($resultado) {
        $this->db->commit();
        return true;
      } else {
        $this->db->rollBack();
        return false;
      }
      
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log("ProfileService::registrarClienteCompleto - Error: " . $e->getMessage());
      return false;
    }
  }

}
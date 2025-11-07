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

}
<?php

namespace App\Modules\Auth\Repositories;

use App\Config\database;
use PDO;

class AuthRepository implements GenericCrud
{

  // creo la variable de tipo PDO
  private PDO $db;

  public function __construct() {
    $con = new Database();
    $this->db = $con->getConexion();
  }

  public function save($data): bool
  {
    // TODO: Implement save() method.
  }

  public function delete($id): bool
  {
    // TODO: Implement delete() method.
  }

  public function findAll(): array
  {
    // TODO: Implement findAll() method.
  }

  public function findById($id): array
  {
    // TODO: Implement findById() method.
  }

}
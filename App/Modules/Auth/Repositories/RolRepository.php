<?php

namespace App\Modules\Auth\Repositories;

use App\Config\Database;
use App\Models\Rol;
use App\Modules\Auth\Repositories\interfaces\IRolDAO;
use DateTime;
use PDO;
use PDOException;

class RolRepository implements IRolDAO
{

  private PDO $db;
  public function __construct() {
    $con = new Database();
    $this->db = $con->getConexion();
  }

  public function save(Rol $data): bool
  {
    // TODO: Implement save() method.
    return false;
  }

  public function delete(int $id): bool
  {
    // TODO: Implement delete() method.
    return false;
  }

  public function findAll(): array
  {
    // TODO: Implement findAll() method.
    return [];
  }

  /**
   * @param $id
   * @return Rol
   */
  public function findById($id): Rol | null
  {
    $rol_arr = [];

    try {

      $query = "SELECT * FROM roles r WHERE r.ROL_CODIGO = :rolId";

      $ps = $this->db->prepare($query);
      $ps->bindParam(':rolId', $id);
      $ps->execute();
      $rol_arr = $ps->fetch(PDO::FETCH_ASSOC);

      if ( count($rol_arr) > 0 ) {

        return $this->getRol($rol_arr);
      }else return null;


    }catch (PDOException $exc) {
      echo '<h1> Error en la base: ' . $exc->getMessage() . '</h1>';
      return null;
    }

  }

  /**
   * @param $rol_arr
   * @return Rol
   */
  private function getRol($rol_arr): Rol{

    if (!empty($rol_arr)) {
      $rol = new Rol();
      $rol->setCodigo($rol_arr['ROL_CODIGO']);
      $rol->setNombre($rol_arr['ROL_NOMBRE']);
      $rol->setFechaRegistro(DateTime::createFromFormat('Y-m-d H:i:s', $rol_arr['ROL_FECHA_REGISTRO']));
      $rol->setFum(DateTime::createFromFormat('Y-m-d H:i:s', $rol_arr['ROL_FUM']));
      $rol->setEstado($rol_arr['ROL_ESTADO']);

      return $rol;
    } else {
      return new Rol();
    }

  }

}
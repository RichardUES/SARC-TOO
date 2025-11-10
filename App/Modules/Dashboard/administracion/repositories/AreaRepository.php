<?php

namespace App\Modules\Dashboard\administracion\repositories;

use App\Config\Database;
use App\Core\GenericCrud;
use App\Models\Area;
use PDOException;
use PDO;

class AreaRepository implements GenericCrud
{

  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getIntance()->getConnection();
  }


  public function save(mixed $area): bool
  {
    try {
      if (isset($area->codigo)) {
        # Actualizamos
        $id = $area->codigo;
        $nombre = $area->nombre;
        $descripcion = $area->descripcion;
        $estado = $area->estado;

        $query = "UPDATE AREAS SET 
                    AREA_NOMBRE=:NOMBRE, AREA_DESCRIPCION=:DESCRIPCION, AREA_ESTADO=:ESTADO 
                    WHERE AREA_CODIGO = :CODIGO";

        $ps = $this->db->prepare($query);
        $ps->bindParam(":CODIGO", $id);
        $ps->bindParam(":NOMBRE", $nombre);
        $ps->bindParam(":DESCRIPCION", $descripcion);
        $ps->bindParam(":ESTADO", $estado);
        $ps->execute();
        
      } else {
        # Guardamos como nuevo
        $nombre = $area->nombre;
        $descripcion = $area->descripcion;

        $query = "INSERT INTO AREA(AREA_NOMBRE, AREA_DESCRIPCION) 
              VALUES(:NOMBRE, :DESCRIPCION)";

        $ps = $this->db->prepare($query);
        $ps->bindParam(":NOMBRE", $nombre);
        $ps->bindParam(":DESCRIPCION", $descripcion);
        $ps->execute();
      }

      return true;
      
    } catch (PDOException $ex) {
      error_log("AreaRepository::save - Error: " . $ex->getMessage());
      return false;
    }
  }

  public function delete(int $id): bool
  {
    return false;
  }

  public function findAll(): array
  {

    try {

      $query = "SELECT * FROM AREAS 
        WHERE AREA_ESTADO = 'ACTIVO'
        ORDER BY AREA_CODIGO ASC";

      $ps = $this->db->prepare($query);
      $ps->execute();
      $areas = $ps->fetchAll(PDO::FETCH_ASSOC);
      $areas_list = [];

      foreach ($areas as $key => $value) {
        $area = new Area();

        $area->setCodigo($value["AREA_CODIGO"]);
        $area->setNombre($value["AREA_NOMBRE"]);
        $area->setDescripcion($value["AREA_DESCRIPCION"]);
        $area->setEstado($value["AREA_ESTADO"]);

        array_push($areas_list, $area);
      }

      return $areas_list;
    } catch (PDOException $ex) {
      // TODO: considerar LOGS
      echo "Error al encontrar areas: " .  $ex->getMessage();
      return [];
    }
  }

  public function findById($id)
  {

    try {
      $query = "SELECT * FROM AREAS WHERE AREA_CODIGO = :CODIGO";
      $ps = $this->db->prepare($query);
      $ps->bindParam(":CODIGO", $id);
      $ps->execute();
      $area_arr = $ps->fetch(PDO::FETCH_ASSOC);

      $area = new Area();
      foreach ($area_arr as $key => $value) {
        $area->setCodigo($value["AREA_CODIGO"]);
        $area->setNombre($value["AREA_NOMBRE"]);
        $area->setDescripcion($value["AREA_DESCRIPCION"]);
        $area->setEstado($value["AREA_ESTADO"]);
      }
      return $area;
      
    } catch (PDOException $ex) {
      return null;
    }
  }
}

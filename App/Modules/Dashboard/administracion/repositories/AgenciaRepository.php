<?php

namespace App\Modules\Dashboard\administracion\repositories;

use App\Config\Database;
use App\Core\GenericCrud;
use App\Models\Agencia;
use PDO;
use PDOException;

class AgenciaRepository implements GenericCrud
{

  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getIntance()->getConnection();
  }

  public function findAll(): array
  {

    $query = "SELECT * FROM AGENCIA AS AGE 
                WHERE AGE.AGE_ESTADO = 'ACTIVO' ORDER BY AGE.AGE_CODIGO ASC";

    try {

      $ps = $this->db->prepare($query);

      $ps->execute();

      $agencias = $ps->fetchAll(PDO::FETCH_ASSOC);

      $list_agencias = [];

      foreach ($agencias as $key => $value) {

        $agencia = new Agencia();

        $agencia->setCodigo($value["AGE_CODIGO"]);
        $agencia->setNombre($value["AGE_NOMBRE"]);
        $agencia->setDireccion($value["AGE_DIRECCION"]);
        $agencia->setTelefono($value["AGE_TELEFONO"]);
        $agencia->setEstado($value["AGE_ESTADO"]);

        array_push($list_agencias, $agencia);
      }

      return $list_agencias;
    } catch (PDOException $ex) {
      // TODO: Evaluar logs
      // echo $ex->getMessage();
      return [];
    }
  } // FIN findAll()

  public function findById(int $id)
  {

    $query = "SELECT * FROM AGENCIA AS A 
                WHERE A.AGE_ESTADO = 'ACTIVO'
                AND A.AGE_CODIGO = :CODIGO";

    try {

      $ps = $this->db->prepare($query);

      $ps->bindParam(":CODIGO", $id);

      $ps->execute();

      $agencia_arr = $ps->fetchAll(PDO::FETCH_ASSOC);

      $agencia = new Agencia();

      foreach ($agencia_arr as $key => $value) {

        $agencia->setCodigo($value["AGE_CODIGO"]);
        $agencia->setNombre($value["AGE_NOMBRE"]);
        $agencia->setDireccion($value["AGE_DIRECCION"]);
        $agencia->setTelefono($value["AGE_TELEFONO"]);
        $agencia->setEstado($value["AGE_ESTADO"]);
      }

      return $agencia;
    } catch (PDOException $ex) {
      // TODO: Evaluar Logs
      // echo $ex->getMessage();
      return null;
    }
  } // FIN findById()

  public function save(mixed $agency): bool
  {

    try {

      if (isset($agency->codigo)) {
        # Actualizamos
        // Traslado las propiedades del objeto a variables
        $id = $agency->codigo;
        $nombre = $agency->nombre;
        $direccion = $agency->direccion;
        $telefono = $agency->telefono;
        $estado = $agency->estado;

        $query = "UPDATE AGENCIA 
                  SET AGE_NOMBRE=:NOMBRE, AGE_DIRECCION=:DIRECCION, AGE_TELEFONO=:TELEFONO, AGE_ESTADO=:ESTADO 
                  WHERE AGE_CODIGO=:CODIGO ";

        $ps = $this->db->prepare($query);
        $ps->bindParam(":CODIGO", $id);
        $ps->bindParam(":NOMBRE", $nombre);
        $ps->bindParam(":DIRECCION", $direccion);
        $ps->bindParam(":TELEFONO", $telefono);
        $ps->bindParam(":ESTADO", $estado);
        $ps->execute();
        
      } else {
        # Guardamos como nuevo
        // Traslado las propiedades del objeto a variables
        $nombre = $agency->nombre;
        $direccion = $agency->direccion;
        $telefono = $agency->telefono;

        $query = "INSERT INTO AGENCIA(AGE_NOMBRE, AGE_DIRECCION, AGE_TELEFONO) 
                VALUES(:NOMBRE, :DIRECCION, :TELEFONO)";

        $ps = $this->db->prepare($query);
        $ps->bindParam(":NOMBRE", $nombre);
        $ps->bindParam(":DIRECCION", $direccion);
        $ps->bindParam(":TELEFONO", $telefono);
        $ps->execute();
      }

      return true;

    } catch (PDOException $ex) {
      error_log("AgenciaRepository::save - Error: " . $ex->getMessage());
      return false;
    }

  } // FIN metodo SAve()

  public function delete(int $id): bool
  {
    return false;
  }
}// FIN Clase

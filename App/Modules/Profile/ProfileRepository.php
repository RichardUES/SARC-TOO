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

  public function createProfileClient(Cliente $client): ?Cliente
  {

    try {

      $this->db->beginTransaction();

      if (isset($client->codigo)) {

        $cod = $client->codigo;
        $usuarioID = $client->usuarioID;
        $fecha_nac = $client->fecha_nac;
        $primer_nombre = $client->primer_nombre;
        $segundo_nombre = $client->segundo_nombre;
        $primer_apellido = $client->primer_apellido;
        $segundo_apellido = $client->segundo_apellido;
        $telefono = $client->telefono;
        $dui = $client->dui;

        // UPDATE
        $query = "UPDATE CLIENTES SET 
          CLI_USUARIO_ID = :USUARIO_ID,
          CLI_FECHA_NAC = :FECHA_NAC, 
          CLI_PRIMER_NOM = :PRIMER_NOMBRE,
          CLI_SEGUNDO_NOM = :SEGUNDO_NOMBRE, 
          CLI_PRIMER_APE = :PRIMER_APELLIDO, 
          CLI_SEGUNDO_APE = :SEGUNDO_APELLIDO, 
          CLI_TELEFONO = :TELEFONO, 
          CLI_DUI = :DUI WHERE CLI_CODIGO = :CODIGO";


        $ps = $this->db->prepare($query);
        $ps->bindParam(":USUARIO_ID", $usuarioID);

        // Paso la feccha como string en formato Y-m-d
        // var_dump($fecha_nac);
        $birthDate = $fecha_nac->format('Y-m-d');
        $ps->bindParam(":FECHA_NAC", $birthDate);

        $ps->bindParam(":PRIMER_NOMBRE", $primer_nombre);
        $ps->bindParam(":SEGUNDO_NOMBRE", $segundo_nombre);
        $ps->bindParam(":PRIMER_APELLIDO", $primer_apellido);
        $ps->bindParam(":SEGUNDO_APELLIDO", $segundo_apellido);
        $ps->bindParam(":TELEFONO", $telefono);
        $ps->bindParam(":DUI", $dui);
        $ps->bindParam(":CODIGO", $cod);

        $this->db->commit();
        return $client;
      }

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

      // Paso la feccha como string en formato Y-m-d
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

      $this->db->commit();

      return $client;


    } catch (PDOException $e) {
      // TODO: considerar Logs
      echo "Error al crear el perfil del cliente: " . $e->getMessage();
      $this->db->rollBack();
      return null;
    }

  }

}
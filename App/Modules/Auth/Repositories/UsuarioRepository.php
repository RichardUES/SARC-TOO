<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Usuario;
use App\Modules\Auth\Repositories\interfaces\IRolDAO;
use App\Modules\Auth\Repositories\interfaces\IUsuarioDAO;
use App\Modules\Auth\Rol;
use PDO;

class UsuarioRepository implements IUsuarioDAO
{

  private IRolDAO $rolRepository;

  public function __construct()
  {

    $this->rolRepository = new RolRepository();

  }

  public function save(Usuario $data): bool
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

  public function findById(int $id): Usuario | null
  {
    try {

      $query = "SELECT * FROM USUARIOS U WHERE U.USU_CODIGO = :USUARIO_ID";

      $ps = $this->db->prepare($query);
      $ps->bindParam(':USUARIO_ID', $id);
      $ps->execute();

      $usuario = $ps->fetch(PDO::FETCH_ASSOC);

      return $this->getUsuario($usuario);

    } catch (\PDOException $e) {
      // echo $e;
      return null;
    }
  }

  private function getUsuario( $usuario ): Usuario {

    $usuario_obj = new Usuario();

    $usuario_obj->setCodigo( $usuario['USU_CODIGO'] );

    // BUSCO EL ROL
    $rol = $this->rolRepository->findById( $usuario['USU_ROL_ID'] );
    $usuario_obj->setRol( $rol );

    $usuario_obj->setUsername( $usuario['USU_USERNAME'] );
    $usuario_obj->setEmail( $usuario['USU_EMAIL'] );
    $usuario_obj->setClave( $usuario['USU_CLAVE'] );
    $usuario_obj->setFechaRegistro( $usuario['USU_FECHA_REGISTRO'] );
    $usuario_obj->setFum( $usuario['USU_FUM'] );
    $usuario_obj->setEstado( $usuario['USU_ESTADO'] );

    return $usuario_obj;

  }

}
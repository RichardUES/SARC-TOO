<?php

namespace App\Modules\Auth;

interface GenericCrud
{

  /**
   * @param $data El objeto que deseas guardar
   * @return bool Devuelve true si se creo | false, en el caso contrario
   */
  public function save($data): bool;

  public function delete($id): bool;

  /**
   * @return array Un Array Asociativo ccon los objetos obtenidos
   */
  public function findAll(): array;

  /**
   * @param $id El identificador con el que se busca el objeto
   * @return array Un Array Asociativo con la informacion del objeto obtenido
   */
  public function findById($id): array;

}
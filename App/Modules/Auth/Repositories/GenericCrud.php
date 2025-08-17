<?php

namespace App\Modules\Auth\Repositories;

interface GenericCrud
{

  public function save($data): bool;
  //public function update($data): bool; // probablemente no lo use
  public function delete($id): bool;
  public function findAll(): array;
  public function findById($id): array;

}
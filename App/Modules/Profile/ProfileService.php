<?php

namespace App\Modules\Profile;

use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Rol;

class ProfileService
{

  private ProfileRepository $profileRepository;

  public function __construct() {
    $this->profileRepository = new ProfileRepository();
  }

  public function createProfileClient(Cliente $client): ?Cliente
  {
    return $this->profileRepository->createProfileClient($client);
  }

  public function updateProfileClient(Cliente $client): void
  {

  }

  public function getProfileByUserID(int $userID): ?Cliente
  {
    return $this->profileRepository->getProfileByUserID($userID);
  }



}
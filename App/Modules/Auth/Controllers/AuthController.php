<?php

namespace App\Modules\Auth\Controllers;

use App\Core\Controller;
use App\Modules\Auth\Repositories\AuthRepository;

class AuthController extends Controller
{
  private AuthRepository $authRepository;

  public function __construct(){
    $this->authRepository = new AuthRepository();
  }

  public function index(){

    $this->view("auth/login");

  }

}
<?php

namespace App\Modules\Auth;

use App\Core\Controller;

class AuthController extends Controller
{
  private AuthRepository $authRepository;

  public function __construct(){
    $this->authRepository = new AuthRepository();
  }

  public function index(){

    $this->view("auth/login");

  }

  public function login(){
    echo "Te estas logueando";
  }

}
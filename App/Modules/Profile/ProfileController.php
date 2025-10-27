<?php

namespace App\Modules\Profile;

use App\Core\Controller;

class ProfileController extends Controller
{

  public function index() {
    session_start();
    $this->view("profile/profile");
  }

}
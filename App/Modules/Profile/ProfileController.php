<?php

namespace App\Modules\Profile;

use App\Core\Controller;

class ProfileController extends Controller
{

  public function index() {
    session_start();
    $this->view("profile/profile");
  }

  public function personal_info() {
    session_start();
    $this->view("profile/personal_info");
  }

}
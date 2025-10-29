<?php

namespace App\Modules\Profile;

use App\Core\Controller;

class ProfileController extends Controller
{

  public function index()
  {
    session_start();
    $this->view("profile/profile");
  }

  public function personal_info()
  {
    session_start();
    $this->view("profile/personal_info");
  }

  public function create_ticket()
  {
    session_start();
    $this->view("profile/create_ticket");
  }

  public function ticket_history()
  {
    session_start();
    $this->view("profile/ticket_history");
  }

  public function notifications()
  {
    session_start();
    $this->view("profile/notifications");
  }

  public function settings()
  {
    session_start();
    $this->view("profile/settings");
  }



}
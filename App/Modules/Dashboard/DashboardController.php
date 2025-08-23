<?php

namespace App\Modules\Dashboard;

use App\Core\Controller;

class DashboardController extends Controller
{

  public function index() {
    $this->view('dashboard/main');
  }

}
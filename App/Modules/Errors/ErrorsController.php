<?php

namespace App\Modules\Errors;

use App\Core\Controller;

class ErrorsController extends Controller
{

  public function not_found(){
    $this->view('errors/404');
  }

  public function unauthorized(){
    $this->view('errors/401');
  }

  public function server_error(){
    $this->view('errors/500');
  }

}
<?php

namespace App\Modules\Home;

use App\Core\Controller;
use App\Modules\Auth\Repositories\interfaces\IRolDAO;
use App\Modules\Auth\Repositories\RolRepository;

class HomeController extends Controller
{

  private IRolDAO $rolRepository;

  public function __construct() {
    $this->rolRepository = new RolRepository();
  }

  /**
   * Página principal del sitio
   * URL: / o /home
   */
  public function index()
  {
    // Datos que queremos pasar a la vista
    $data = [
      'title' => 'Luz el Faro',
    ];

    // Cargar la vista usando el método heredado
    $this->view('home', $data);
  }

  /**
   * Página About/Acerca de
   * URL: /home/about
   */
  public function about()
  {
    $data = [
      'title' => 'Acerca de Nosotros',
      'company_info' => [
        'name' => 'Luz el Faro',
        'founded' => '2025',
        'employees' => 4,
        'mission' => 'Crear soluciones innovadoras para nuestros clientes'
      ]
    ];

    $this->view('about', $data);
  }

  /**
   * Contacto con formulario
   * URL: /home/contact
   */
  public function contact()
  {
    // Si es POST, procesar el formulario
    if ($this->isPost()) {
      $name = $_POST['name'] ?? '';
      $email = $_POST['email'] ?? '';
      $message = $_POST['message'] ?? '';

      // Validar datos (ejemplo simple)
      if (empty($name) || empty($email) || empty($message)) {
        $data = [
          'title' => 'Contacto',
          'error' => 'Todos los campos son obligatorios',
          'old_data' => $_POST // Para mantener los datos en el form
        ];
      } else {
        // Aquí procesar el envío (email, base de datos, etc.)
        // Por ahora solo simular éxito
        $data = [
          'title' => 'Contacto',
          'success' => '¡Mensaje enviado correctamente! Te contactaremos pronto.'
        ];
      }
    } else {
      // Si es GET, mostrar formulario vacío
      $data = [
        'title' => 'Contacto'
      ];
    }

    $this->view('contact', $data);
  }

}
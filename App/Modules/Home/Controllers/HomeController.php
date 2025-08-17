<?php

namespace App\Modules\Home\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
  /**
   * Página principal del sitio
   * URL: / o /home
   */
  public function index()
  {
    // Datos que queremos pasar a la vista
    $data = [
      'title' => 'Bienvenido al Sistema',
      'user_name' => 'Richard',
      'stats' => [
        'total_users' => 150,
        'total_products' => 45,
        'total_orders' => 320
      ],
      'recent_activities' => [
        'Usuario Juan se registró',
        'Producto "Laptop Dell" agregado',
        'Orden #1234 completada'
      ]
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
        'name' => 'Mi Empresa',
        'founded' => '2020',
        'employees' => 25,
        'mission' => 'Crear soluciones innovadoras para nuestros clientes'
      ]
    ];

    $this->view('about', $data);
  }

  /**
   * API endpoint para obtener estadísticas
   * URL: /home/stats (para AJAX)
   */
  public function stats()
  {
    // Simular datos de base de datos
    $stats = [
      'users' => 150,
      'products' => 45,
      'orders' => 320,
      'revenue' => 25000
    ];

    // Devolver como JSON usando el método heredado
    $this->json($stats);
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

  /**
   * Redirigir a dashboard (ejemplo de redirect)
   * URL: /home/dashboard
   */
  public function dashboard()
  {
    // Usar el método redirect heredado
    $this->redirect('dashboard');
  }
}
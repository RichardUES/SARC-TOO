<?php

/**
 * Funcion que me ayuda a eliminar una variables de sesion
 * USU comun, por ejemplo cuando tengo una variable de sesion de error
 * quiero eliminarla una vez mostrada, no interesa que viva durante toda la sesion
 * @param $name
 * @return mixed
 */
function deleteSession($name)
{

  if (isset($_SESSION[$name])) {

    $_SESSION[$name] = null;

    unset($_SESSION[$name]);
  }
  return $name;
}

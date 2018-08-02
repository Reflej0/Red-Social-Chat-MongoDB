<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['mongoDB_connection'] = new MongoDB\Driver\Manager("mongodb://localhost:27017");
/*Es una forma de definir un "singleton" en CodeIgniter, esto se realiza porque la libreria new MongoDB\Driver\Manager
no soporta el metodo close para cerrar la conexion, y aunque en MongoDB(local) no es un problema
en la nube la cantidad de conexiones abiertas se cobra.*/
/*El acceso a esta variable desde cualquier otro controlador/modelo se realiza de la siguiente manera
$this->config->item('mongoDB_connection')
*/
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imagenes extends CI_Controller 
{
	/*Esta funcion recibe una llamada de AJAX e inserta o actualiza la imagen de un usuario. */
	public function ajax_newImagePerfil()
	{
		$this->load->library('session'); // Variables de sesion.
		$this->load->model('ImagenesModel'); //Carga del modelo.
		$this->load->model('UsuariosModel'); //Carga del modelo.
		$this->load->config('MongoDB'); // Carga de configuracion de MongoDB.
		if ($this->input->server('REQUEST_METHOD') != 'POST' || !$_FILES)
			exit();
		$imagen = $_FILES["file"];
		$usuarioId = $this->UsuariosModel->getId($_SESSION['apodo']);
		//Se realiza el "insert/update" de la imagen a traves de ImagenesModel(MongoDB)
		$this->ImagenesModel->newImagen($imagen, $usuarioId);
		exit();
	}
}
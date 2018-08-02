<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posteos extends CI_Controller 
{
	/*Esta funcion recibe una llamada de AJAX y devuelve todos los posteos de los usuarios seguidos por un usuario*/
	public function ajax_getPosteos()
	{
		//Carga de auxiliares.
		$this->load->helper('url');
		$this->load->view('css'); // Carga de Css para la vista.
		$this->load->view('js'); // Carga de js para al vista.
		$this->load->library('session'); // Variables de sesion.
		$this->load->model('UsuariosModel'); // Carga del modelo.
		$this->load->model('PosteosModel'); //Carga del modelo.
		$this->load->config('MongoDB'); // Carga de configuracion de MongoDB.
		//Si no viene de un POST.
		if ($this->input->server('REQUEST_METHOD') != 'POST')
			exit();
		//Obtengo los usuarios a los que sigue el usuario logeado.
		$usuarioId = $this->UsuariosModel->getId($_SESSION['apodo']);
		$seguidos = $this->UsuariosModel->getSeguidos($usuarioId);
		//Obtengo los posteos de los usuarios a los que sigue el usuario logeado y los del usuario mismo.
		$posteos = array();
		array_push($posteos, $this->PosteosModel->searchPosteobyusuarioId($usuarioId));
		foreach($seguidos as $idSeguido)
			array_push($posteos, $this->PosteosModel->searchPosteobyusuarioId($idSeguido['idUsuario2']));
		$posteos = $this->parsePosteos($posteos);
		//Mando los posteos a la vista.
		echo json_encode($posteos);
		exit();
	}

	/*Esta funcion recibe una llamada de AJAX, e inserta un nuevo posteo.*/
	public function ajax_newPosteo()
	{
		//Carga de auxiliares.
		$this->load->helper('url');
		$this->load->view('css'); // Carga de Css para la vista.
		$this->load->view('js'); // Carga de js para al vista.
		$this->load->library('session'); // Variables de sesion.
		$this->load->model('UsuariosModel'); // Carga del modelo.
		$this->load->model('PosteosModel'); //Carga del modelo.
		$this->load->config('MongoDB'); // Carga de configuracion de MongoDB.
		//Si no viene de un POST.
		if ($this->input->server('REQUEST_METHOD') != 'POST')
			exit();
		$mensaje = $this->input->post('mensaje'); // Se recibe el mensaje del posteo, el mensaje incluye hashtags.
		$hashtags = $this->getHashtags($mensaje); // Se extran los hashtags del mensaje.
		//Se crea el posteo recopilando los datos necesarios.
		$posteo = array(
		"usuarioId" => $this->UsuariosModel->getId($_SESSION['apodo']), 
		"usuarioApodo" => $_SESSION['apodo'],
		"mensaje" => $mensaje, 
		"hashtags" =>$hashtags);
		//Se realiza el "insert" del posteo a traves de PosteosModel(MongoDB)
		$this->PosteosModel->newPosteo($posteo);
	}

	/*Esta funcion recibe una llamada de AJAX e inserta un nuevo comentario en un posteo*/
	public function ajax_sendComentario()
	{
		//Carga de auxiliares.
		$this->load->helper('url');
		$this->load->view('css'); // Carga de Css para la vista.
		$this->load->view('js'); // Carga de js para al vista.
		$this->load->library('session'); // Variables de sesion.
		$this->load->model('UsuariosModel'); // Carga del modelo.
		$this->load->model('PosteosModel'); //Carga del modelo.
		$this->load->config('MongoDB'); // Carga de configuracion de MongoDB.
		//Si no viene de un POST.
		if ($this->input->server('REQUEST_METHOD') != 'POST')
			exit();
		//Recepcion de datos desde la vista.
		$posteoId = $this->input->post('posteoId');
		$comentario = $this->input->post('comentario');
		//Se obtiene el idUsuario en base a su apodo(único).
		$usuarioId = $this->UsuariosModel->getId($_SESSION['apodo']);
		//Se realiza el "insert" del mensaje a traves de PosteosModel(MongoDB)
		$this->PosteosModel->newComentario($posteoId, $usuarioId, $_SESSION['apodo'], $comentario);
	}

	/*Esta funcion adecua el formato de los posteos recibidos para pasarlos a la vista.*/
	public function parsePosteos($contenedor_posteos)
	{
		if(!is_array($contenedor_posteos) || empty($contenedor_posteos)) return NULL;
		$posteosok = array();
		foreach($contenedor_posteos as $posteos)
			foreach($posteos as $posteo)
				array_push($posteosok, $posteo);
		return $posteosok;
	}

	/*Esta funcion busca hashtags dentro de un mensaje*/
	public function getHashtags(&$mensaje)
	{
		if(strpos($mensaje, "#") == FALSE) return NULL;
		$hashtags = explode("#", $mensaje);
		$mensaje = $hashtags[0]; // El mensaje sin los hashtag queda en la primera posicion.
		unset($hashtags[0]); // El array contendra hashtag en todos sus posiciones, menos la primera.
		return $hashtags;
	}
}
?>
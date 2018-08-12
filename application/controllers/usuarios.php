<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function index()
	{
		//Carga de auxiliares.
		$this->load->helper('url');
		$this->load->view('css'); // Carga de Css para la vista.
		$this->load->view('js'); // Carga de js para al vista.
		$this->load->model('UsuariosModel'); // Carga del modelo.
		$this->load->model('PosteosModel'); //Carga del modelo.
		$this->load->config('MongoDB'); // Carga de configuracion de MongoDB.
		//Login o creación de nuevo usuario.
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			//Si el apodo o el password no esta seteado, no se puede realizar login ni creacion.
			if($this->input->post('apodo') == NULL || $this->input->post('password')== NULL || $this->input->post('email')== NULL)
				$this->load->view('usuarios/login', ['excepcion' => "Complete todos los campos"]);

			$apodo = $this->input->post('apodo');
			$email = $this->input->post('email');
			$contraseña = md5($this->input->post('contraseña'));
			//Login de usuario.
			if($this->input->post('ingresar') !=NULL && $this->input->post('ingresar') == 'true')
			{
				$resp = $this->UsuariosModel->login(array('apodo' => $apodo, 'contraseña' => $contraseña));
				if($resp == -1)
					$this->load->view('usuarios/login', ['excepcion' => "Usuario inexistente"]);
				if($resp == 0)
					$this->load->view('usuarios/login', ['excepcion' => "Contraseña incorrecta"]);
				elseif($resp == 1)
				{
					$this->session->set_userdata('apodo', $apodo);
					redirect('/usuarios/inicio');
				}
			}
			//Registracion de usuario.
			else if($this->input->post('registrar') !=NULL && $this->input->post('registrar') == 'true')
			{
				$resp = $this->UsuariosModel->newUsuario(array('apodo' => $apodo, 'email'=> $email, 'contraseña' => $contraseña));
				if($resp == 0) 
					$this->load->view('usuarios/login', ['excepcion' => "Usuario existente"]);
				else
					redirect('/usuarios/inicio');
			}
			//Caso excepcional o malintencionado.
			else
				$this->load->view('usuarios/login', ['excepcion' => "Error excepcional"]);
		}
		//Acceso por GET. Es improbable que se acceda por POST estando logeado.
		else
		{
			if($this->session->has_userdata('apodo'))
				redirect('/usuarios/inicio');
			else
				$this->load->view('usuarios/login', ['excepcion' => ""]);
		}
	}

	public function inicio()
	{
		//Carga de auxiliares.
		$this->load->helper('url');
		$this->load->view('css'); // Carga de Css para la vista.
		$this->load->view('js'); // Carga de js para al vista.
		$this->load->model('UsuariosModel'); // Carga del modelo.
		$this->load->model('PosteosModel'); //Carga del modelo.
		$this->load->model('ImagenesModel'); //Carga del modelo.
		$this->load->config('MongoDB'); // Carga de configuracion de MongoDB.
		//Si el usuario intenta acceder sin estar logeado.
		if(!$this->session->has_userdata('apodo'))
				redirect('/usuarios/index');
		//Obtengo el idUsuario en base a su apodo.
		$idUsuario = $this->UsuariosModel->getId($this->session->has_userdata('apodo'));
		//Busco la imagen del usuario.
		$imagen = $this->ImagenesModel->searchImagenbyUsuarioId($idUsuario);
		//Compruebo si realmente tiene una imagen.
		if(!empty($imagen))
			$imagen = $imagen[0]->imagen;
		//Paso los parametros a la vista.
		$data = ["idUsuario"=> $idUsuario, "imagen" => $imagen];
		$this->load->view('usuarios/inicio', $data);
	}

	/*Esta funcion recibe una llamada de AJAX, y si el apodo existe, realiza una nueva relacion en la seguidor .*/
	public function ajax_newRelacion()
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
		$apodo = $this->input->post('apodo'); // Se recibe el mensaje del posteo, el mensaje incluye hashtags.
		$idUsuarioaSeguir = $this->UsuariosModel->getId($apodo); // Se busca si existe el apodo del usuario a seguir.
		if($idUsuarioaSeguir)
		{
			//Se realiza la asoacion.
			$this->UsuariosModel->addSeguidor($this->UsuariosModel->getId($this->session->has_userdata('apodo')), $idUsuarioaSeguir);
			echo 1; // Respuesta para la vista.
		}
		else
			echo 0; // Respuesta para la vista.
		exit();
	}
}

//$this->PosteosModel->newPosteo(array("usuarioId" => 3, "mensaje" => "asd", "hashtags" => ["array", "pum"]));
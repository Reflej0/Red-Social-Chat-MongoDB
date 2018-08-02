<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UsuariosModel extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->CI = get_instance();
    }
	public function newUsuario($usuario)
	{
		if(!is_array($usuario)) return -1;
		if($this->login($usuario) === 0) return 0; //El metodo login en realidad es un getUsuariobyapodo.
    	$this->db->insert('usuario', $usuario);
    	return 1;
	}

	public function updateUsuario($usuario)
	{
		if(!is_array($usuario)) return -1;
    	$this->db->replace('usuario', $usuario); /*La entidad $usuario cuenta con el id*/
	}

	public function deleteUsuario($usuario)
	{
		if(!is_array($usuario)) return -1;
		$id = $usuario['id'];
		$this->db->delete('usuario', array('id' => $id));
	}

	public function addSeguidor($idUsuario1, $idUsuario2)
	{
		$this->db->insert('seguidor', array('idUsuario1' => $idUsuario1, 'idUsuario2' => $idUsuario2));
	}

	public function getId($apodo)
	{
		$resp = $this->db->select('id')->from('usuario')->where('apodo', $apodo)->get();
		if($resp->num_rows() == 0) return NULL; // Si la búsqueda no dio resultado no hay un usuario con el apodo buscado.
		else return $resp->row()->id;
	}

	public function login($usuario)
	{
		$resp = $this->db->select('*')->from('usuario')->where('apodo', $usuario['apodo'])->get();
		if($resp->num_rows() == 0) return -1;
		if($resp->row()->contraseña === $usuario['contraseña']) return 1;
		return 0;
	}

	public function getSeguidos($idUsuario)
	{
		return ($resp = $this->db->select('idUsuario2')->from('seguidor')->where('idUsuario1', $idUsuario)->get()->result_array());
	}
}
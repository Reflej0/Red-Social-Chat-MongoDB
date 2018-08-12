<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ImagenesModel extends CI_Model
{
	private $db;

	/*Se utiliza un constructor modificado para inicializar la variable $db en el entorno de la clase ImagenesModel
	con el valor de la configuracion seteada en MongoDB.php(config)*/
	public function __construct() 
	{
		$ci =& get_instance();
		$config = $ci->load->config('MongoDB');
		$this->db = $ci->config->item('mongoDB_connection');
	}

	/*Esta funcion recibe una imagen por parametro a insertarse en la coleccion imagenes*/
	public function newImagen($imagen, $usuarioId)
	{
		$query = new MongoDB\Driver\BulkWrite;
		$update = ['$set' => [
			"usuarioId" => intval($usuarioId),
			"imagen" => new MongoDB\BSON\Binary(file_get_contents($imagen["tmp_name"]), MongoDB\BSON\Binary::TYPE_GENERIC),
		]];
		//Upsert establece que de existir el documento dentro de la coleccion bajo el criterio la condicion, se actualice, de no existir se inserte.
		$adicional = ['upsert' => true];
		$condicion = ["usuarioId" => intval($usuarioId)];
		$query->update($condicion, $update, $adicional);
		$this->db->executeBulkWrite('reflej0.imagenes', $query);
		//EXAMPLE QUERY.
		/*
			db.imagenes.update({'usuarioId' : NumberInt(2)}, 
			{'$set' : {'usuarioId' : NumberInt(2),
			"imagen": BinData(0, "reemplazarpordatosbinarios")}}, 
			{'multi' : false, 'upsert' : true})
		*/
	}

	/*Esta funcion devuelve la imagen de un usuario.*/
	public function searchImagenbyUsuarioId($usuarioId)
	{
		$condicion = ['usuarioId' => intval($usuarioId)]; 
    	$query = new MongoDB\Driver\Query($condicion);  
    	$res = $this->db->executeQuery("reflej0.imagenes", $query);
    	return $res->toArray();
		//EXAMPLE QUERY.
		/*
			db.imagenes.find({"usuarioId" : 1}).limit(1)
		*/
	}
}
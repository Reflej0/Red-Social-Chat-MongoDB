<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PosteosModel extends CI_Model
{
	private $db;

	/*Se utiliza un constructor modificado para inicializar la variable $db en el entorno de la clase PosteosModel
	con el valor de la configuracion seteada en MongoDB.php(config)*/
	public function __construct() 
	{
		$ci =& get_instance();
		$config = $ci->load->config('MongoDB');
		$this->db = $ci->config->item('mongoDB_connection');
	}

	/*Esta funcion recibe un array $posteo por parametro y realiza el insert en la coleccion posteos.*/
	public function newPosteo($posteo)
	{
		$query = new MongoDB\Driver\BulkWrite;
		$posteo = ["usuarioId" => intval($posteo['usuarioId']),
		"usuarioApodo" => $posteo['usuarioApodo'],
		"mensaje" => $posteo['mensaje'],
		"fechaCreacion" => new MongoDB\BSON\UTCDateTime(), /*Fecha BSON*/
		"hashtags" => $posteo['hashtags']];
		$query->insert($posteo);
		$this->db->executeBulkWrite('reflej0.posteos', $query);
		//EXAMPLE QUERY.
		//Consulta de ejemplo en MongoDB crudo.
		/*db.getCollection("posteos").insert
		({"usuarioId" : NumberInt(1),
		"mensaje" : "Hola soy un posteo del usuario con id 1",
		"fechaCreacion" : new Date(),
		"hashtags" : ["#asd", "#yii", "#you"]});*/
	}

	/*Esta funcion recibe una condicion(mensaje) para buscar todos los posteos que contengan el mensaje buscado.*/
	public function searchPosteobyMensaje($mensaje)
	{
    	$condicion = ['mensaje' => new MongoDB\BSON\Regex ('^'.$mensaje)]; 
    	$query = new MongoDB\Driver\Query($condicion);     
    	$res = $this->db->executeQuery("reflej0.posteos", $query);
    	return $res->toArray();
    	//EXAMPLE QUERY.
    	//Consulta de ejemplo en MongoDB crudo.
    	/*db.posteos.find({mensaje:/Hola/});*/
	}

	/*Esta funcion recibe una condicion(idUsuario) para buscar todos los posteos que coincidan con el idusuario buscado.*/
	public function searchPosteobyusuarioId($usuarioId)
	{
    	$condicion = ['usuarioId' => intval($usuarioId)]; 
    	$order = ["sort" => ["fechaCreacion" => -1]]; // Para ordenar los posteos para ver los mas recientes.
    	$query = new MongoDB\Driver\Query($condicion, $order);  
    	$res = $this->db->executeQuery("reflej0.posteos", $query);
    	return $res->toArray();
    	//EXAMPLE QUERY.
    	//Consulta de ejemplo en MongoDB crudo.
    	/*db.posteos.find({usuarioId:1});*/
	}
	/*Esta fybcuin recibe un posteo y un comentario que debe relacionarse al posteo.*/
	public function newComentario($posteoId, $usuarioId, $usuarioApodo, $comentario)
	{
		$query = new MongoDB\Driver\BulkWrite;
		$condicion = ['_id' => new MongoDB\BSON\ObjectId($posteoId)];
		$update = ['$push'=> ['comentarios'=>['_id' => new MongoDB\BSON\ObjectId(), 'usuarioId' => intval($usuarioId), 'usuarioApodo' => $usuarioApodo, 'mensaje' => $comentario, 
		'fechaCreacion' => new MongoDB\BSON\UTCDateTime()]]];
		try 
		{
            $query->update($condicion, $update);
            $this->db->executeBulkWrite('reflej0.posteos', $query);     
        } 
        catch(Exception $e) 
        {
        	print_r($e->getMessage());
        	exit();
        }
        //EXAMPLE QUERY.
        /*	PUSH (DOCUMENTO EMBEBIDO)
		db.getCollection("posteos").update(
		{ _id : ObjectId("5b6056accf8e8c17f82a8d90") },
		{ $push : { Comentarios:  {usuarioId:2, mensaje:"32"}} })
	*/
	}
}
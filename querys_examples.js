//Insercion de un documento en coleccion posteos, tratamiento de Entero, String, Fecha y Array.
db.posteos.insert
		({usuarioId : NumberInt(1),
		mensaje : "Hola soy un posteo del usuario con id 1",
		fechaCreacion : new Date(),
		hashtags : ["#asd", "#yii", "#you"]});

//Busqueda en coleccion posteos, de todos los posteos que contengan en su mensaje la frase Hola.
db.posteos.find({mensaje:/Hola/});

//Busqueda en coleccion posteos, de todos los posteos que coincidan con el usuarioid 1.
db.posteos.find({idUsuario:1});

//Insercion de un comentario(documento embebido) dentro de un documento especifico(por _id) en coleccion posteos.
db.posteos.update(
		{ _id : ObjectId("5b6056accf8e8c17f82a8d90") },
		{ $push : { Comentarios:  {usuarioId:2, mensaje:"32"}} });

//Actualizacion de un comentario ya existente(documento embebido) dentro de un documento especifico(por _id) en coleccion posteos.
db.posteos.update(
		{ _id : ObjectId("5b5fd0b3e13384b63434fa6b") },
		{ $set : { Comentarios:  {usuarioId:4, mensaje:"new"}} });

//Actualizacion de un comentario ya existente(documento embebido) dentro de un documento(por_Comentarios.id_) en coleccion posteos.
//Si varios posteos tendrian dentro comentarios con el mismo id 1 se actualizarian.
db.posteos.update(
    { Comentarios._id: 1 }, 
    { $set: { "Comentarios.$.mensaje": "Estoy actualizado" } });
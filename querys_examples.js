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
//Tratamiento de _id dentro de un documento embebido y fecha.
//IMPORTANTE: Siempre hay que restarle uno al mes, es decir mes 8, se pone como 7.
db.posteos.update(
		{ _id : ObjectId("5b61d673bb0d4d1e5191fe8a") },
		{ $push : { Comentarios:  {_id: new ObjectId(), usuarioId:2, mensaje:"Comentando...", fechaCreacion: new Date(2018,07,01)}}});

//Actualizacion de un comentario ya existente(documento embebido) dentro de un documento especifico(por _id) en coleccion posteos.
db.posteos.update(
		{ _id : ObjectId("5b5fd0b3e13384b63434fa6b") },
		{ $set : { Comentarios:  {usuarioId:4, mensaje:"new"}} });

//Actualizacion de un comentario ya existente(documento embebido) dentro de un documento(por_Comentarios.id_) en coleccion posteos.
//Si varios posteos tendrian dentro comentarios con el mismo id 1 se actualizarian.
db.posteos.update(
    { Comentarios._id: 1 }, 
    { $set: { "Comentarios.$.mensaje": "Estoy actualizado" } });

//Numero de documentos que cumplen con una determinada condición.
db.posteos.find({usuarioId : 1}).count();

//Eliminacion de un comentario(documento embebido) dentro de un documento(por _id) en coleccion posteos.
db.posteos.update(
		{ _id : ObjectId("5b61d673bb0d4d1e5191fe8a") },
		{ $unset : { Comentarios:  {_id:ObjectId("idbuscado")}}});

//Numero de comentarios (documento embebido) que contiene un determinado posteo.
db.posteos.find({_id : ObjectId("5b61d673bb0d4d1e5191fe8a")})[0].Comentarios.length;

//Lo mismo que lo anterior pero utilizando una funcion de agregacion.
db.posteos.aggregate( 
    [ 
        { $match : {'_id': ObjectId("5b61d673bb0d4d1e5191fe8a")}}, 
        { $unwind : "$Comentarios" }, 
        { $group : { _id : null, number : { $sum : 1 } } } 
    ] 
);

//Obtengo el posteo con la fechaCreacion mas nueva.
db.posteos.find({}).sort({"fechaCreacion" : -1}).limit(1);

//Obtengo el posteo con la fechaCreacion mas vieja.
db.posteos.find({}).sort({"fechaCreacion" : +1}).limit(1);

//Posteo con mas comentarios. El $Comentarios debe ser si o si un array.
db.posteos.aggregate([
    {$project:{
        id: "$_id", count: {$size:{"$ifNull":["$Comentarios",[]]} }
    }},
    {$group: {
        _id: null, 
        max: { $max: "$count" }
    }}
]);

//Hashtag mas utilizado.
db.posteos.aggregate([
  {"$unwind":"$hashtags"},
  {"$sortByCount":"$hashtags"},
  {"$limit":1}
])

//Busqueda en coleccion posteos, aplicando un filtro por fecha.

db.posteos.find({
    fechaCreacion: {
        $gte: ISODate("2018-07-30"),
        $lt: ISODate("2018-07-31")
    }
})

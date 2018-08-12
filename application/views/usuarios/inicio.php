<form id="inicio_usuario" action="" method="post" enctype="multipart/form-data">
<input id="amigo" type="text" name="amigo" placeholder="Tipee el nombre de su amigo">
<button id="agregar" type="button">Agregar amigo</button><br>
<textarea id="posteo" rows="4" cols="50" placeholder="Publica un nuevo posteo" maxlength="255"/> </textarea>
<button id="publicar" type="button">Publicar</button>
<input id="user_image_file" type='file' name="user_image_file" onchange="uploadImage(this, 'user_image');"/>
<?php if(!empty($imagen)): //Si el usuario tiene una imagen asociada entonces se debe tratar.?>
<img id="user_image" src="data:jpeg;base64,<?=base64_encode($imagen->getData())?>"/>
<?php endif; ?>
<?php if(empty($imagen)): //Si el usuario no tiene una imagen asociada.?>
<img id="user_image" src="#" alt=""/>
<?php endif; ?>
<div id="muro">
</div>
</form>

<script>
//Acciones que se realizan al cargar la página.
$(document).ready(function() {
	tratarPosteos(); // Recupero los posteos al cargar la página.
    setInterval(tratarPosteos, 10000); //Voy tratando los nuevos posteos tras un tiempo determinado.
    //Con esto me aseguro que el boton de agregar comentario a una publicacion funcione adecuadamente.
    $(document).on('click','.boton_comentario',function()
    {
    	sendComentario(this.id, $('.'+this.id).val()); //El id del posteo y el mensaje en sí.
    })
    //
    $("#user_image").width(100).height(100); // Con esto me aseguro de que si el usuaio tenia una imagen cargada se mantenga la proporcion default establecida en 100x100.
});

//Al pulsar el boton publicar.
$("#publicar").click(function() {
    //Esta es la forma de comprobar que un TEXTAREA esta vacio.
    if(!$.trim($("posteo").val()))
        return;

    $.ajax({
        url: "<?php echo base_url(); ?>/index.php/posteos/ajax_newPosteo",
        type: 'POST',
        data: {mensaje: $('#posteo').val()},
        dataType: 'json',
        success: function() {}
    });
});

//Al pulsar el boton agregar un amigo.
$("#agregar").click(function() {
  $.ajax({
        url: "<?php echo base_url(); ?>/index.php/usuarios/ajax_newRelacion",
        type: 'POST',
        data: {apodo: $('#amigo').val()},
        dataType: 'json',
        success: function(resp) {
        	if(resp == 1)
        		alert("Amigo agregado");
        	else
        		alert("Amigo no encontrado");
        }
    });
});

//Esta funcion realiza el tratamiento de la foto subida por el usuario.
function uploadImage(inputfile, inputimage)
{
    if(!setImageSrc(inputfile, inputimage)) // Esta funcion valida la imagen y la mantiene disponible en una resolucion de 100x100.
        return;
    //Manipulacion del archivo subido por el usuario.
    var file_data = $('#user_image_file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    //Llamada de AJAX para el almacenamiento del archivo en MongoDB.
    $.ajax({
    url: "<?php echo base_url(); ?>/index.php/imagenes/ajax_newImagePerfil",
    type: "POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData:false
    });

}

//Esta funcion recibe un JSON con los posteos y se encarga de realizar el tratamiento visual de los mismos.
function tratarPosteos(posteos)
{
	$("#muro").html(""); //Como primera medida se limpia el muro.
	var posteos = getPosteos(); // Se obtienen los posteos.
	for(var i in posteos) // Se recorren todos los posteos obtenidos.
    	putPosteo(posteos[i]); // Se exhiben los posteos en el muro.
}

//Esta funcion devuelve un JSON con los posteos.
function getPosteos()
{
	var resp; // Variable de retorno.
	$.ajax
	({
        url: "<?php echo base_url(); ?>/index.php/posteos/ajax_getPosteos",
        type: 'POST',
        async: false, //Esto es necesario para que la llamada de AJAX devuelva correctamente el JSON tras el success.
        dataType: 'json',
        success: function(posteos)
        {
        	resp = posteos;
            console.clear(); // Para ocultar las reiteradas llamadas de AJAX.
        }
    })
    return resp;
}

//Esta funcion recibe un posteo como parámetro y se encarga de insertarlo visualmente en el muro.
function putPosteo(posteo)
{
	$("#muro").append('<div id="'+'1">');
	$("#muro").append('<label name="usuario">'+posteo["usuarioApodo"]+'</label>');
	$("#muro").append(' ');
	$("#muro").append('<label name="fecha">'+adecuarFecha(posteo["fechaCreacion"])+'</label><br>');
	$("#muro").append('<label name="texto">'+posteo["mensaje"]+'</label><br>');
	if (posteo['comentarios'] !== null) // Compruebo que el posteo tenga comentarios.
		putComentarios(posteo['comentarios']); // Llamo a la funcion que trata los comentarios.
	$("#muro").append('<input type="text" placeholder="Publique su comentario" class="'+posteo["_id"]["$oid"]+'"">');
	$("#muro").append('<button class="boton_comentario" type="button" id="'+posteo["_id"]["$oid"]+'">'+"Publicar comentario"+'</button><br>');
	$("#muro").append('</div>');
}

//Esta funcion recibe un array de comentarios como parametros y se encarga de insertarlo visualmente junto con el comentario.
function putComentarios(comentarios)
{
	for(var i in comentarios)  // Se recorren todos los comentarios obtenidos.
	{
		$("#muro").append("&nbsp;&nbsp;&nbsp;&nbsp;"); // Espacio horizontal(tab).
		$("#muro").append('<label name="usuario_comentario">'+comentarios[i]['usuarioApodo']+':</label>');
		$("#muro").append('<label name="comentario">'+comentarios[i]['mensaje']+'</label><br>');
	}
}

//Esta funcion realiza una llamada de AJAX asignando un nuevo comentario a un posteo.
function sendComentario(posteoId, comentario)
{
	$.ajax
	({
        url: "<?php echo base_url(); ?>/index.php/posteos/ajax_sendComentario",
        type: 'POST',
        data:{posteoId: posteoId, comentario: comentario},
        dataType: 'json',
        success: function(posteos)
        {
        	tratarPosteos(); // Al comentar actualizo los posteos y por consiguiente los comentarios.
        }
    })
}
</script>
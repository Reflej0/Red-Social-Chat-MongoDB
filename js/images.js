//Esta funcion realiza la manipulacion de la imagen subida por el usuario.
function setImageSrc(input, idimgsrc) 
{
    if(!validarFile(input))
    {
        $("#"+idimgsrc).attr('src', "#");
        return false;
    }
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#"+idimgsrc)
                .attr('src', e.target.result)
                .width(100)
                .height(100);
        };
        reader.readAsDataURL(input.files[0]);
    }
    return true;
}

//Funcion que valida el archivo ingresado al input. Formato y Tamaño.
function validarFile(all)
{
    //EXTENSIONES Y TAMANO PERMITIDO.
    var extensiones_permitidas = [".png", ".bmp", ".jpg", ".jpeg", ".doc", ".gif"];
    var tamano = 1; // EXPRESADO EN MB.
    var rutayarchivo = all.value;
    var ultimo_punto = all.value.lastIndexOf(".");
    var extension = rutayarchivo.slice(ultimo_punto, rutayarchivo.length);
    if(extensiones_permitidas.indexOf(extension) == -1)
    {
        alert("Extensión de archivo no valida");
        document.getElementById(all.id).value = "";
        return false; // Si la extension es no válida ya no chequeo lo de abajo.
    }
    if((all.files[0].size / 1048576) > tamano)
    {
        alert("El archivo no puede superar los "+tamano+"MB");
        document.getElementById(all.id).value = "";
        return false;
    }
    return true;
}
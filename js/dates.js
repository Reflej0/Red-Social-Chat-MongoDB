//Esta funcion adecua el formato de la fecha.
function adecuarFecha(fecha)
{
	var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
	return new Date(Number(fecha["$date"]["$numberLong"])).toLocaleTimeString("es-AR", options);
}
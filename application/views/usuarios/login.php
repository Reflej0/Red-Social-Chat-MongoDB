<!DOCTYPE html>
<html>
<title>Ejemplo de MongoDB</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>

<header class="w3-container w3-teal">
  <h1>Ingreso al Sistema</h1>
</header>

<div class="w3-container w3-half w3-margin-top">
<form method= "post" class="w3-container w3-card-4">

<p>
<input name="apodo" class="w3-input" type="text" style="width:90%" required maxlength="255">
<label>Apodo</label></p>
<p>
<input name="email" class="w3-input" type="email" style="width:90%" required maxlength="255">
<label>Email</label></p>
<p>
<input name="password" class="w3-input" type="password" style="width:90%" required maxlength="255">
<label>Contraseña</label></p>


<button id="ingresar" name="ingresar" value="true" class="w3-button w3-section w3-teal w3-ripple"> Ingresar </button>
<button id="registrar" name="registrar" value="true" class="w3-button w3-section w3-teal w3-ripple"> Registrar </button>
</form>

</div>

</body>
</html> 

<script>
      if ('<?php echo $excepcion ?>' != "") alert('<?php echo $excepcion ?>');
</script>
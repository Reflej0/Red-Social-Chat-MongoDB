# Red-Social-Chat-MongoDB
Un desarrollo básico que integra CodeIgniter y MongoDB.

# Sobre el desarrollo
El objetivo personal del desarrollo es comprender la utilización de MongoDB de forma nativa y asociada con PHP.

# Bases de Datos
El desarrollo utiliza una base de datos MySQL para el apartado de usuarios y además una base de datos No-SQL(MongoDB) para el sector de posteos y comentarios realizados por los usuarios. <br />Se realiza utilizando la filosofía de que no es necesario almacenar todo en MongoDB sino solo los datos que cumplen con las características de ser almacenados en una base de datos no relacional.

# Funcionamiento
Al 01/08/2018 el funcionamiento consta de una registración/login y un sector de inicio donde se puede:<br />
 * Agregar a un usuario por apodo (Se utiliza el concepto de seguir a un usuario) :white_check_mark:.<br />
 * Realizar una publicación (Se pueden agregar hashtags#, actualmente no cumplen ninguna función) :white_check_mark:.<br />
 * Visualizar mis publicaciones, y las publicaciones de los usuarios que sigo :white_check_mark:.<br />
 * Realizar comentarios sobre mis publicaciones y las publicaciones de los usuarios que sigo :white_check_mark:.<br />

Actualmente disminuyendo el tiempo de respuesta para obtener las publicaciones se podría asimilar mas a un chat.

# Extras
 * Se adjunta el archivo querys_example que contienen consultas nativas de MongoDB que se pueden implementar con el esquema dado.  :large_blue_circle:<br />
 * Se adjunta el archivo MongoDB y CodeIgniter instalación, contiene los pasos para instalar MongoDB(cliente/servidor) en Windows y el plugin en CodeIgniter(:x:aunque este no se utilizó) es su lugar se utilizó el plugin de PHP nativo new MongoDB\Driver\Manager<br />
 * Se adjunta el diagrama de clases en formato .vpp (Visual Paradigm).
 
 # Próximamente
  * - [ ] Version online.
  * - [ ] Sección de estadísticas (Usuario con mas posteos, Hashtags mas populares, etc).

# trabajo-appbancaria
Trabajo de javascript de crear una mini app bancaria con javascript php css html ...

Hay tres modelos(BancoCliente,BancoCuentas,BancoMovimientos) que corresponde a las tres tablas de la 
base de datos.
Cada modelo tiene su controlador por el que deben pasar todas las peticiones 
que se dirigen al modelo.
Existe un controlador principal en la carpeta controller donde se decide a que subcontrolador se 
quiere ir o en su caso a que vista.
El fichero index1.php incluye el controlador principal.
En la carpeta views se encuentra una carpeta js con todos los escripts de cada vista,
la carpeta css con un archivo de estilos, la carpeta layouts donde se encuentra el 
layout que utilizan todas las vistas, la carpeta plantillas con las plantillas de las vistas.
En la misma carpeta views se los archivos de las vistas que incluiran su plantilla determinada,
su archivo js y un titulo de pagina.

## instruciones de despliegue

- Tener instalado php Apache Y mysql.
- Clonar el repositorio.
- Ejecutar el archivo creardb.php que permite crear y borrar la base de datos.
- Ejecutar el archivo user.sql para crear el usuario utilizado y darle los permisos adecuados sobre la bas e de datos.
- Ejecutar el fichero index1.php que es el punto de entrada de la aplicacion.



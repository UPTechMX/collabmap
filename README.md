
Instalación
Importar la base de datos (CMEmpty.sql) en MySQL
Crear un usuario en MySQL con todos los privilegios a la base de datos creada
En el archivo basepdo.php, configurar el usuario, contraseña y base de datos creados. (ejemplo: basepdo.php.config)

crear y dar permisos de escritura a los siguientes directorios.

- chkPhotos
- consultationDocuments
- externalFiles
- problemsPhotos

instalar un servidor SMTP en el servidor

Instalar geoserver en el directorio /usr/local/geoserver o /usr/share/geoserver
Instalar el plugin Vector Tiles y MySQL desde el repositorio de geoserver http://geoserver.org/release/stable/
Dentro de geoserver, crear un espacio de trabajo llamado CMPy
En almacenamiento de datos, crear un almacenamiento MySQL hacia la base de datos definida y agregarla al espacio de trabajo creado.
Crear una capa con nombre KMLGeometries y que sea una vista del almacenamiento de datos con el siguiente query de SQL SELECT * FROM KMLGeometries
Definirle el SRS declarado como EPSG:4326
Generar los encuadres de manera automática.
En la pestaña de Tiles (Teselas) y cache
activar la opción application/vnd.mapbox-vector-tile


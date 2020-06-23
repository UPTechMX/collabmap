Requerimientos:
- Apache2
- PHP 7.4
- MariaDB 10.4
- Geoserver 2.17.1

Instalación

Importar la base de datos (CMEmpty.sql) en MariaDB

Crear un usuario en MySQL con todos los privilegios a la base de datos creada
Cambiar los datos de conexión a la base de datos en el archivo basepdo.php.config y renombrarlo a basepdo.php

Crear los siguientes directorios en el directorio raiz del sistema y dar permisos de escritura.

- chkPhotos
- consultationDocuments
- externalFiles
- problemsPhotos

instalar un servidor SMTP en el servidor que permita enviar correos desde PHP (Postfix permite esto) . 

Instalar geoserver en el directorio /usr/local/geoserver o /usr/share/geoserver (https://docs.geoserver.org/stable/en/user/)
Instalar las extensiones Vector Tiles y MySQL desde el repositorio de geoserver http://geoserver.org/release/stable/ 
	Seguir las instrucciones de instalación de extensiones de GeoServer (https://docs.geoserver.org/stable/en/user/extensions/vectortiles/install.html) (https://docs.geoserver.org/stable/en/user/data/database/mysql.html)

Copiar el archivo web.xml al directorio WEB-INF (esto permite obtener información desde otras direcciones)

Dentro de geoserver, crear un espacio de trabajo llamado CMPy (https://docs.geoserver.org/stable/en/user/data/webadmin/workspaces.html)

En almacenamiento de datos, crear un almacenamiento MySQL hacia la base de datos definida y agregarla al espacio de trabajo creado. (https://docs.geoserver.org/stable/en/user/data/webadmin/stores.html) (https://docs.geoserver.org/stable/en/user/data/database/mysql.html)

Crear una capa con nombre KMLGeometries y que sea una vista del almacenamiento de datos con el siguiente query de SQL SELECT * FROM KMLGeometries (https://docs.geoserver.org/stable/en/user/data/webadmin/layers.html)

Definirle el SRS declarado como EPSG:4326

Generar los encuadres de manera automática.

En la pestaña de Tiles (Teselas) y cache activar la opción application/vnd.mapbox-vector-tile

Para acceder entra a http://<dirección de instalación>/admin

u:admin

p:root

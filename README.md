# Requirements
- Apache2
- PHP 7.4
- MariaDB 10.4
- Geoserver 2.17.1

# Installation

Import the database (CMEmpty.sql) into MariaDB

Create a user in MySQL with all the privileges.
Change the connection data to the database in the ```basepdo.php.config``` file and rename it to ```basepdo.php``` .

Create the following directories in the root directory and grant write permissions.

- chkPhotos
- consultationDocuments
- externalFiles
- problemsPhotos

Install an SMTP server on to send emails from PHP (Postfix is recommended).

Install Geoserver in the folowing directroy ```/usr/local/geoserver o /usr/share/geoserver (https://docs.geoserver.org/stable/en/user/) ```

Install the Vector Tiles and MySQL extensions from the geoserver repository: (http://geoserver.org/release/stable/) 

Follow the GeoServer extensions installation instructions as described in: 
(https://docs.geoserver.org/stable/en/user/extensions/vectortiles/install.html) 
(https://docs.geoserver.org/stable/en/user/data/database/mysql.html)

Copy the web.xml file to the WEB-INF directory (this will be allow the system to pull data from other addresses)

Inside Geoserver, create a workspace called *CMPy* (https://docs.geoserver.org/stable/en/user/data/webadmin/workspaces.html)

In data storage, create a MySQL storage towards the defined database and add it to the created workspace. (https://docs.geoserver.org/stable/en/user/data/webadmin/stores.html) (https://docs.geoserver.org/stable/en/user/data/database/mysql.html)

Create a layer named KMLGeometries and make it a view of the data storage with the following SQL query:
```SELECT * FROM KMLGeometries ```
(https://docs.geoserver.org/stable/en/user/data/webadmin/layers.html)

Define the SRS declared as EPSG: 4326

Generar los encuadres de manera automática.

Generate the frames automatically.

In the Tiles and cache tab, activate the application: ```application/vnd.mapbox-vector-tile```

To access go to ```http://<dirección de instalación>/admin```
The default username and passwrod will be:
```u:admin
p:root```

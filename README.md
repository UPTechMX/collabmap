# CollabData

CollabData is a digital platform that captures the needs, challenges and ground realities of the communities, strengthens public consultations and simplifies the analysis of collected data with powerful visualizations. The analysis can be presented in the form of heatmaps, spatial word clouds, emotion maps and gender statistics to transform data into information that can be easily utilized for decision making.

## Documentation

[Brochure](https://docs.google.com/presentation/d/1cn-GMQRDLWvNWqt6snN6bx9FEzwd0IcFKbU8wzVbTV8/edit?usp=sharing)
[Case studies](https://docs.google.com/presentation/d/1_XhcyJJ7WuMTIlEh21QwxI0BRl5TeK6XX_eF5ZZb9tg/edit?usp=sharing)
[User manual](https://docs.google.com/presentation/d/10AiJfQyb_M3Tf4GhkzQ3Xa-gcBT2wVNmjqb6hwNB50s/edit?usp=sharing)
[Technical report](https://docs.google.com/document/d/1o8Xe606uJvrQkNmL5yw4hHcx1wMyXCl3OzUY-HqBwBc/edit?usp=sharing)

## Requirements

Apache2
PHP 7.4
MariaDB 10.4
Geoserver 2.17.1

## Installation

1. Import the database (CMEmpty.sql) in MariaDB
2. Create a user in MySQL with all the privileges to the created database Change the connection data to the database in the basepdo.php.config file and rename it to basepdo.php
3. Create the following directories in the root directory of the system and give write permissions.

- chkPhotos
- consultationDocuments
- externalFiles
- problemsPhotos

4. Install an SMTP server on the server that allows you to send emails from PHP (Postfix allows this).

5. Install geoserver in the / usr / local / geoserver or / usr / share / geoserver directory (https://docs.geoserver.org/stable/en/user/) Install the Vector Tiles and MySQL extensions from the geoserver repository http: //geoserver.org/release/stable/ Follow the GeoServer extensions installation instructions (https://docs.geoserver.org/stable/en/user/extensions/vectortiles/install.html) (https: // docs .geoserver.org / stable / en / user / data / database / mysql.html)
6. Copy the web.xml file to the WEB-INF directory (this allows information to be obtained from other addresses)
7. Inside geoserver, create a workspace called CMPy (https://docs.geoserver.org/stable/en/user/data/webadmin/workspaces.html)
8. In data storage, create a MySQL storage towards the defined database and add it to the created workspace. (https://docs.geoserver.org/stable/en/user/data/webadmin/stores.html) (https://docs.geoserver.org/stable/en/user/data/database/mysql.html)
9. Create a layer named KMLGeometries and make it a view of the data storage with the following SQL query 
```SELECT * FROM KMLGeometries``` (https://docs.geoserver.org/stable/en/user/data/webadmin/layers.html)
10. Define the SRS declared as EPSG: 4326
11. Generate frames automatically.
12. In the Tiles and cache tab, activate the application / vnd.mapbox-vector-tile option
To access go to http: // <installation address> / admin
u: admin p: root

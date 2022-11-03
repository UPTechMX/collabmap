#!/bin/bash

delete(){

    sudo rm -Rf /usr/share/geoserver/data_dir/gwc/$1_KMLGeometries/*

}


delete $1


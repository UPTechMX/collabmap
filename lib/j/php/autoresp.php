<?php

	include_once '../j.func.php';
	$marcas = ["No cuento con automóbil","Abarth",
"Acura",
"Alfa Romeo",
"Aston Martin",
"Audi",
"BAIC",
"Bentley",
"BMW",
"Buick",
"Cadillac",
"Chang'an",
"Chevrolet",
"Chrysler",
"Dodge",
"DFSK",
"FAW",
"Ferrari",
"Fiat",
"Ford Motor Company de México",
"GMC",
"Honda",
"Hyundai",
"Infiniti",
"JAC",
"Jaguar",
"Jeep",
"Kia",
"Lamborghini",
"Land Rover",
"Lincoln",
"Lotus",
"Maserati",
"Mazda",
"McLaren Automotive",
"Mercedes-Benz",
"MINI",
"Mitsubishi Motors de México",
"Nissan Mexicana",
"Peugeot",
"Porsche",
"Ram",
"Renault",
"Rolls Royce Motor Cars",
"SEAT",
"Smart",
"SRT",
"Subaru",
"Suzuki",
"Tesla Motors",
"Toyota",
"Volkswagen",
"Volvo Cars",
"VÜHL"];

// print2($marcas);
$stmt = $db -> prepare("INSERT INTO RespuestasGrupos SET grupo = 5, respuesta = ?");
foreach ($marcas as $m) {
	$stmt -> execute([$m]);
	echo "$m<br/>";
}
echo 'FIN';

?>
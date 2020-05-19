<?php

include_once '../../../lib/j/j.func.php';

checaAcceso(5); // checaAcceso analysis;

// print2($_POST);
$LJ = '';
$where = '';
$fields = '';
$i = 0;
$whereAtt = array();

foreach ($_POST['attrs'] as $k => $attr) {
	$logic = $attr['logic'] == 1 ? "AND" : "OR" ;
	$logic = $i==0 ? "" : $logic;

	switch ($attr['type']) {
		case 'string':
			if($attr['optVal'] != ''){
				$LJ .= " LEFT JOIN GeometriesAttributes ga$k ON ga$k.geometriesId = g.id AND ga$k.attributeId = :attrId$k";
				$LJ .= " LEFT JOIN KMLAttributes a$k ON a$k.id = :attrAId$k";
				
				if($i != 0){
					$where = " ($where) ";
				}
				$where .= " $logic ga$k.value = :val$k";
				$fields .= ", a$k.name as aName$k, ga$k.value as aValue$k ";

				$arr["val$k"] = $attr['optVal'];
				$arr["attrId$k"] = $attr['id'];
				$arr["attrAId$k"] = $attr['id'];
			}else{
				continue 2;
			}
			break;
		case 'float':
			if($attr['numVal'] != ''){
				$LJ .= " LEFT JOIN GeometriesAttributes ga$k ON ga$k.geometriesId = g.id AND ga$k.attributeId = :attrId$k";
				$LJ .= " LEFT JOIN KMLAttributes a$k ON a$k.id = :attrAId$k";
				if($i != 0){
					$where = " ($where) ";
				}

				$where .= " $logic CAST(ga$k.value as DECIMAL(15,10)) $attr[inequality] :val$k";
				$fields .= ", a$k.name as aName$k, ga$k.value as aValue$k ";
				
				$arr["val$k"] = $attr['numVal'];
				$arr["attrId$k"] = $attr['id'];
				$arr["attrAId$k"] = $attr['id'];

			}else{
				continue 2;
			}
			break;
		
		default:
			# code...
			break;
	}
	// echo "I2: $i\n\n";
	$i++;
}

$where = $where != ''? " AND ($where)": '' ;
// echo "\n $LJ \n $where \n";

$sql = "
	SELECT
	CASE
		WHEN (g.identifier = -1) THEN g.id
		ELSE g.identifier
	END as idGroup, 
	g.id, 
	CASE
		WHEN (g.identifier = -1) THEN g.id
		ELSE g.identifier
	END as identifier $fields
	FROM KMLGeometries g
	$LJ 
	WHERE g.KMLId = :kmlId $where
";
// echo "\n\n $sql \n\n";

$arr['kmlId'] = $_POST['kmlId'];
// print2($arr);
$stmt = $db->prepare($sql);

$stmt -> execute($arr);

$geoms = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

echo atj($geoms);




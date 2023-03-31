<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$user = $_GET['id'];

	// ssh grantm@located-platial.geog.mcgill.ca -L 5555:localhost:5432
    $dbconn = pg_connect("host=localhost port=5432 dbname=platialk user=platial password=platial");

	$geom = array();
	$row = 1;
	if (($handle = fopen("test.csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	    	if ($row > 1) {
		        $id = intval($data[0]);
		        $cat = trim($data[1]);
		        $lat = floatval($data[2]);
		        $lng = floatval($data[3]);
		        $query = "INSERT INTO points (\"user\", id, cat, geom) VALUES (".$user.",".$id.",'".$cat."',st_setsrid(st_makepoint(".$lng.",".$lat."),4326));";
		        	//echo $query . "\n";
		        pg_query($query) or die(pg_last_error());
		        //$geom[] = $lng . " " . $lat;
		    }
	        $row++;
	    }
	    fclose($handle);
	}
	/* $str = "SELECT st_asgeojson(ST_ConvexHull(ST_GeomFromText('MULTIPOINT(" . implode(",",$geom) . ")'))) as json;";
	$result = pg_query($str) or die(pg_last_error());

	header('Content-Type: application/json; charset=utf-8');

	while($row = pg_fetch_object($result)) {
		echo $row->json; //. "\n";
	} */

?>
<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	header('Content-Type: application/json; charset=utf-8');

	$id = $_GET['id'];

	// ssh grantm@located-platial.geog.mcgill.ca -L 5555:localhost:5432
    $dbconn = pg_connect("host=localhost port=5432 dbname=platialk user=platial password=platial");

	$query = "SELECT row_to_json(fc) as json FROM (SELECT 'FeatureCollection' AS type, array_to_json(array_agg(f)) AS features FROM (SELECT 'Feature' AS type, ST_AsGeoJSON(geom)::json as geometry, (SELECT row_to_json(t) FROM (SELECT pid, cat) AS t) AS properties FROM points WHERE id = ".$id.") AS f) AS fc"; 

	$result = pg_query($query) or die(pg_last_error());
	while($row = pg_fetch_object($result)) {
		echo $row->json;
	}
	/* $str = "SELECT st_asgeojson(ST_ConvexHull(ST_GeomFromText('MULTIPOINT(" . implode(",",$geom) . ")'))) as json;";
	$result = pg_query($str) or die(pg_last_error());

	

	while($row = pg_fetch_object($result)) {
		 //. "\n";
	} */

?>
<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$id = $_GET['id'];
	$pid = $_GET['pid'];
	$k = $_GET['k'];
	$ts = $_GET['ts'];

	require('db.php');

	// $query = "SELECT a.pid, st_distance(a.geom, b.geom) as dist FROM points a, (select geom from points where pid = ".$pid." AND id = ".$id.") b WHERE a.id = ".$id." ORDER BY dist LIMIT 1 OFFSET 1"; 
	$query = "SELECT a.pid, b.s, st_distance(a.geom, b.geom) as dist FROM points a, (select a.geom, b.sig[".$ts."] as s from points a, temppopsigs b where a.cat = b.cat AND a.pid = ".$pid." AND a.id = ".$id.") b WHERE a.id = ".$id." ORDER BY dist LIMIT 1 OFFSET 1";

	$result = pg_query($query) or die(pg_last_error());
	while($row = pg_fetch_object($result)) {
		$qid = intval($row->pid); // . "\t" . $row->dist . "\n";
		$sig = floatval($row->s);
		$query2 = "SELECT st_asgeojson(st_setsrid(st_envelope(st_extent(geom)),4326)) as json, st_area(st_transform(st_setsrid(st_envelope(st_extent(geom)),4326),3857)) as area FROM (SELECT a.pid, a.geom, st_distance(a.geom, b.geom) as dist FROM points a, (select geom from points where pid = ".$qid." AND id = ".$id.") b, temppopsigs c WHERE a.id = ".$id." AND c.cat = a.cat AND c.sig[".$ts."] >= ".$sig." ORDER BY dist LIMIT ".$k.") c";
		// $query2 = "SELECT st_asgeojson(st_convexhull(St_collect(geom))) as json FROM (SELECT a.pid, a.geom, st_distance(a.geom, b.geom) as dist FROM points a, (select geom from points where pid = ".$qid." AND id = ".$id.") b WHERE a.id = ".$id." ORDER BY dist LIMIT ".$k.") c";
			// echo $query2 . "\n";

		$res = pg_query($query2) or die(pg_last_error());
		while($row = pg_fetch_object($res)) {
			header('Content-Type: application/json; charset=utf-8');
			$out = json_decode($row->json);
			$out->properties = (Object)array();
			$out->properties->area = round($row->area);
			echo json_encode($out);
		}
	}
	/* $str = "SELECT st_asgeojson(ST_ConvexHull(ST_GeomFromText('MULTIPOINT(" . implode(",",$geom) . ")'))) as json;";
	$result = pg_query($str) or die(pg_last_error());

	

	while($row = pg_fetch_object($result)) {
		 //. "\n";
	} */

?>
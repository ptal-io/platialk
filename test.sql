SELECT St_collect(geom) FROM (


	SELECT a.pid, a.geom, st_distance(a.geom, b.geom) as dist FROM points a, (select geom from points where pid = 5 AND id = 1) b WHERE a.id = 1 ORDER BY dist LIMIT 5) SELECT St_collect(geom) FROM (SELECT a.pid, a.geom, st_distance(a.geom, b.geom) as dist FROM points a, (select geom from points where pid = 17 AND id = 1) b WHERE a.id = 1 ORDER BY dist LIMIT 5) c
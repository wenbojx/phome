<?php 


$con = mysql_connect("192.168.1.58", "remote", "db2202");
$db_selected = mysql_select_db("BookDB",$con);

$type = 1;

$begin = microtime(TRUE);

function mysql_fetch_all($result) {
	while($row=mysql_fetch_array($result)) {
		$return[] = $row;
	}
	return $return;
}

if($type==1){
	for ($i=0; $i<10; $i++){
	$sql = 'SELECT b.IDX Volumeid, b.Volumetitle, b.Volumesort, b.Isvip, b.Status, b.Showtype, b.Words AS Vwords, b.Volumedesc, c.IDX, c.Chaptertitle, c.Chaptersort, c.Showtime, c.Words
FROM (

SELECT a.IDX, a.Volumesort, a.NID, a.Volumetitle, a.Showtype, a.Words, a.Status, a.Isvip, a.Volumedesc
FROM novelvolume a
WHERE a.NID =90805
AND a.Status <>2
)b
LEFT JOIN novelchapter c ON b.NID = c.NID
AND b.IDX = c.Volumeid
AND c.Locked =0
AND c.Status =2
ORDER BY b.Volumesort, c.Chaptersort';
	$result = mysql_query($sql,$con);
	$datas = mysql_fetch_all($result);
	}
	$end = microtime(TRUE);
	$time = $end-$begin;
	echo "执行了".$time."s<br>";
}
else{
	for ($i=0; $i<10; $i++){
	$sql = 'SELECT IDX, Volumesort, NID, Volumetitle, Showtype, Words, Status, Isvip, Volumedesc
FROM novelvolume
WHERE NID =90805
AND Status !=2 ';
	$result = mysql_query($sql,$con);
	$datas1 = mysql_fetch_all($result);

	$ids = array();
	foreach($datas1 as $v){
		$ids[] = $v['IDX'];
	}
	$instr = implode(',', $ids);
	
	$sql = "SELECT *
FROM `novelchapter`
WHERE Volumeid
IN ( {$instr} ) ORDER BY Chaptersort";
	$result2 = mysql_query($sql,$con);
	$datas2 = mysql_fetch_all($result2);
	
	}
	$end = microtime(TRUE);
	$time = $end-$begin;
	echo "执行了".$time."s<br>";
	
}

//print_r($datas);

$end = microtime(TRUE);
$time = $end-$begin;
echo "执行了".$time."s<br>";


?>
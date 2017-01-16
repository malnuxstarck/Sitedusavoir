<?php
 $ip = ip2long($_SERVER['REMOTE_ADDR']);
//Requête
$query = $bdd->prepare('INSERT INTO forum_whosonline VALUES(:id,NOW(),:ip) ON DUPLICATE KEY UPDATE online_time = NOW() , online_id = :id');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->bindValue(':ip', $ip, PDO::PARAM_INT);

$query->execute();
$query->CloseCursor();




$query=$bdd->prepare('DELETE FROM forum_whosonline WHERE online_time < SUBDATE(NOW(),INTERVAL 5 MINUTE)');

$query->execute();
$query->CloseCursor();
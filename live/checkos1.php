<?php

$db2 = mysql_connect('192.168.22.218', 'live_sales', 'RHfGeMcD5pY2VzN7', true); 
$db3 = mysql_connect('192.168.20.218', 'live_usa', 'UTqK5jYqSRuFqyrS', true);

mysql_select_db('live_sales', $db2);
mysql_select_db('live_usa', $db3);

$select_qb1 = "SELECT history.ModifiedDate FROM history WHERE history.TableName = 'template'";
$result = mysql_query($select_qb1, $db2);
// mysql_fetch_assoc($result); 
$updateTime = (mysql_fetch_assoc($result));
$TimeNow = time();


$retStr = $updateTime['ModifiedDate'];

echo "Last Update: ".strtotime($retStr)."</br>".$TimeNow;

$TimeSinceLastUpdate = time() - 18000 - strtotime($retStr);

echo "</br></br>".$TimeSinceLastUpdate."</br></br></br>";

if ($TimeSinceLastUpdate > 0) 
    {
    echo "Sending Email";
    $MinutesSinceLastUpdate = ($TimeSinceLastUpdate + 600) /60;
    $to = "andy@nimfl.com, jeremy@tileredi.com";
    //$to = "andy@nimfl.com";
    $subject = "Open Sync Did not sync..db0_sales";
    $message = "Open Sync Has Not Syncronized with db0_sales in the NOC for: ".intval($MinutesSinceLastUpdate)." Minutes";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";	
    $headers .= 'From: <site@tileredi.com>' . "\r\n";
    mail($to,$subject,$message,$headers);
    
    }
    
    
$select_qb1 = "SELECT history.ModifiedDate FROM history WHERE history.TableName = 'template'";
$result = mysql_query($select_qb1, $db3);
// mysql_fetch_assoc($result); 
$updateTime = (mysql_fetch_assoc($result));
$TimeNow = time();


$retStr = $updateTime['ModifiedDate'];

echo "Last Update: ".strtotime($retStr)."</br>".$TimeNow;

$TimeSinceLastUpdate = time() - 18000 - strtotime($retStr);

echo "</br></br>".$TimeSinceLastUpdate;

if ($TimeSinceLastUpdate > 0) 
    {
    echo "Sending Email";
    $MinutesSinceLastUpdate = ($TimeSinceLastUpdate + 1200) /60;
    $to = "andy@nimfl.com, jeremy@tileredi.com";
    //$to = "andy@nimfl.com";
    $subject = "Open Sync Did not sync..db0_usa";
    $message = "Open Sync Has Not Syncronized with db0_usa in the NOC for: ".intval($MinutesSinceLastUpdate)." Minutes";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";	
    $headers .= 'From: <site@tileredi.com>' . "\r\n";
    mail($to,$subject,$message,$headers);
    
    }

?>
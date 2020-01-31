<?php
try { 
$pdo = new PDO('mysql:host=localhost;dbname=pos_db','root','root');
 // echo 'Connection successful';   
    
}catch(PDOException $f) {
    
    echo $f ->getmessage();
    
}
?>
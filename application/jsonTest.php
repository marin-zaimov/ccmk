<?php

$list = get_loaded_extensions(); 
$list2 = array_map('strtolower',$list); 
sort($list2); 
echo '<pre>'.print_r($list2,true).'</pre>'; 


//phpinfo();
// $connect = mysql_connect("localhost","root","marin");

// if (!$connect) {
//     die("Couldn't connect:" . mysql_error());
// }

// mysql_select_db("ccmk");

// $sql=mysql_query("select * from User");
// //echo $sql;
// while($row=mysql_fetch_assoc($sql)) $output[]=$row;
// print(json_encode($output));

// mysql_close(); ?>
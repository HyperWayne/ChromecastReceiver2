<?php  
    $file = fopen("message.txt","w");
    $message = "lalalalala";
    fwrite($file, $message);
	fclose($file);
    echo "string";
 ?>
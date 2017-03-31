<?php
$con = mysql_connect('localhost:3306', 'root', 'RohNam22');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
$action=$_POST["action"];

    $sql = "select f_name ,picture  from ( select frnd_id from friends_list where user_id= '$action') as t1, user where t1.frnd_id = user.user_id ";
    mysql_select_db('php_db');

    $retval = mysql_query( $sql, $con );
if (!$retval) {
   
}
echo"<div id ='div1'>";
   echo "<table width='550'id='frndlist'>";
   echo "<tr bgcolor='#f1f1f1'><td><b>Friends</b></td></tr>";
   while($row = mysql_fetch_array($retval , MYSQL_ASSOC))
    {
        $name = $row['f_name'];
        echo "<tr bgcolor='#f1f1f1'><td height ='30><a href='#' >$name</a></td></tr>";
        
    }
 echo "</table>";
 echo"</div>";
 echo"";
    //mysql_close($conn);
    


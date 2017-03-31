<?php
$con = mysql_connect('localhost:3306', 'root', 'RohNam22');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';
//Get online and offline friends for the username
if(isset($_POST['username'])) {
  $inputname = clean($_POST['username']);
//$inputname = 'nam@scu.edu';
    $sql = "select f_name ,picture  from ( select frnd_id from friends_list where user_id= '$inputname') as t1, user where t1.frnd_id = user.user_id ";
    mysql_select_db('php_db');

    $retval = mysql_query( $sql, $con );
if (!$retval) {
    //$output = '<p>No friends found ,add friends !!!☺ .</p>';
}

//    echo "<table>";
//     echo "<tr bgcolor='#f1f1f1'><td><b>Friends</b></td></tr>";
$name = array();
    while($row = mysql_fetch_array($retval , MYSQL_ASSOC))
    {
        $name[] = $row['f_name'];
      //  echo "<tr bgcolor='#f1f1f1'><td>$name</td></tr>";
//echo json_encode($name);
    }
 echo json_encode(array('result1'=>$name));
    mysql_close($con);
}

?>
<?php 

function clean($str){
	return trim(mysql_real_escape_string($str));
}
?>
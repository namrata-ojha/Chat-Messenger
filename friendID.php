<?php  
//get friendname userid	
		if((isset($_POST['friendname'])))
		{
			$conn = mysql_connect('localhost:3306', 'root', 'RohNam22');
			if (!$conn) {
				die('Could not connect: ' . mysql_error());
			}
		$username = clean($_POST['friendname']);
		
		$sql = "SELECT user_id FROM user WHERE f_name='$username'";
		mysql_select_db('php_db');
		$retval = mysql_query( $sql, $conn );
		if(! $retval )
		{
			die('Could not get data: ' . mysql_error());
		}
		while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
		{
			echo "". $row['user_id'] ."";
		}
			
		
		
		}
// 		if((isset($_POST['dispname'])))
// 		{
// 		$username = clean($_POST['dispname']);	
// 		//$username="test3";
// 		$con = mysql_connect($sql_server, $sql_username, $sql_password);
// 		$query = "SELECT displayname FROM ". $sql_database_name .".reg_info WHERE username='%s'";
// 		$query = sprintf($query, $username);
// 		$result = mysql_query($query, $con);
// 		$rowr = mysql_fetch_array($result);
// 		echo "". $rowr['displayname'] ."";
// 		}
// //Database update on userlogout
// 		if((isset($_GET['logout'])))
// 		{
// 		$username = clean($_GET['logout']);	
// 		$con = mysql_connect($sql_server, $sql_username, $sql_password);
// 		$query = "UPDATE ". $sql_database_name .".reg_info SET onlinestatus=0 WHERE username='%s'";
// 		$query = sprintf($query, $username);
// 		mysql_query($query, $con);
// 		echo "<script>window.location='index.php';</script>";
// 		}
mysql_close($conn);
?>
// <?php
function clean($str){
	return trim(mysql_real_escape_string($str));
}
?>

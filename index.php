<?php
session_start();
if(isset($_GET['logout'])){
    //FUNCTION FOR WRITING LOG IN HTML FILE
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
    fclose($fp);

    session_destroy();
    header("Location: index.php"); //Redirect the user
}



$conn = mysql_connect('localhost:3306', 'root', 'RohNam22');
function sqlConnect()
{
    if (!$GLOBALS['conn']) {
        die('Could not connect: ' . mysql_error());
    }
    echo 'Connected successfully';

}

function build_table($a){
    //echo $a ;
    // start table
    // $html = '<table>';
    foreach($a as $value) {
        echo "$value";


    }


}


function loginForm(){
    echo'
<div id="loginform">
<form action="index.php" method="post">
<p>Please enter your Username & Password to continue:</p>
<label for="name">UserName:</label>
<input type="text" name="name" id="name" />
<label for="name">Password:</label>
<input type="text" name="password" id="password" />
<input type="submit" name="enter" id="enter" value="Enter" />
</form>
<a href="signup.php">Sign up here</a>
</div>
<div id="response"></div>
';
}

if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
        $_SESSION['password'] = stripslashes(htmlspecialchars($_POST['password']));
    }
    else{
        echo '<span class="error">Please type in your Username & Password </span>';
    }
}


?>


<!DOCTYPE html>
<html>
<head>
    <title>chat</title>
    <link type="text/css" rel="stylesheet" href="style.css" />
<style>
body {
	background-color: #eeeeee;
	padding:0;
	margin:0 auto;
	font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
	font-size:11px;
}
</style>
<link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
<link type="text/css" rel="stylesheet" media="all" href="css/screen.css" />
    </head>
<body>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/chat.js"></script>
<?php
if(!isset($_SESSION['name'])and (!isset($_SESSION['password']))){
    loginForm();
}
else{
$username =$_SESSION['name'] ;
$password=$_SESSION['password'] ;

$sql="SELECT * FROM user WHERE user_id='$username' and Password='$password'";
mysql_select_db('php_db');
$result=mysql_query($sql);
// print "$result";
$count=mysql_num_rows($result);

if($count<1){
    echo "Wrong Username or Password";
    mysql_close($GLOBALS['conn']);
    loginForm();
}else {

//        echo "$count";
print"login_success $count ";
mysql_close($GLOBALS['conn']);
?>
<div id="wrapper">
    <div id="menu">
        <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
        <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
         <div style="clear:both"></div>
        
    </div>

    <div id="friendsList">

        <!--            <form name="FriendsList" action="">-->

        <?php
        $con = mysql_connect('localhost:3306', 'root', 'RohNam22');
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
        //echo 'Connected successfully';
        //Get online and offline friends for the username
        //                if(isset($_POST['username'])) {
        $inputname = $_SESSION['name'];
        //echo "$inputname";
        $sql = "select f_name ,picture  from ( select frnd_id from friends_list where user_id= '$inputname') as t1, user where t1.frnd_id = user.user_id ";
        mysql_select_db('php_db');

        $retval = mysql_query( $sql, $con );
        if (!$retval) {
            $output = '<p>No friends found ,add friends !!! .</p>';
        }

        //echo "<table width ='400' id ='frndTbl'name='tbl'>";
        //echo "<tr bgcolor='#f1f1f1'><td height='50'><b>Friends</b></td></tr>";
        $name = array();
        while($row = mysql_fetch_assoc($retval ))
        {
            $name[] = $row['f_name'];
            $picture =$row['picture'];
            //echo "<tr bgcolor='#f1f1f1' class='tblrow' href=$name><td height='50'>$name</td></tr>";
            //   echo"$name";


        }
       // build_table($name);
        //  print_r( array_values( $name ));
        //echo json_encode(array('result1'=>$names));


        ?>
        <!--                foreach($name as $value) {-->
        <!--                <div>$value</div>;-->
        <!---->
        <!--                }-->
        <!--            </div>-->
        </body>
</html>



<!--            </form>-->
</div>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js">
</script>
<script type="text/javascript";>
var friendname;


$(document).ready(function(){

$("#exit").click(function(){
var exit = confirm("Are you sure you want to end the session?");
if(exit==true){window.location = 'index.php?logout=true';}
});





});

$(document).ready(function(){
	$username ="<?php echo $_SESSION['name']; ?>";
	var value="";
	 $('#friendsList').html(value); 
	 $.post("FriendsList.php", { username: $username  }, function(data){
      
	 var result=JSON.parse(data); 
	 //console.log(result.result1); 
	  $.each(result.result1, function(index, val) {
// 		  console.log(val); 
		  value+="<input type=\"radio\" id=\"btn\" style=\"color:green\"class=\"rad\" onclick=\"friendname='"+val+"';getfriendid();\" value=\'"+val+"'\/><span class=\"highlight\">"+val+"</span><br>";
	  });
	  $('#friendsList').html(value); 
	 });
});

$("#div1").on('click',  function(){
  	console.log("clicked"); 
});

function getfriendid(){
	$.post("friendID.php",{ friendname: friendname },function(data){
		//console.log(friendname);
		friendid=data;
		 console.log(friendid);
		 javascript:chatWith(friendname); 
		 //window.open ("chat.php?id_noticia=<?php echo friendid; ?>", "Editar notícia", "location=1, status=1, scrollbars=1, width=800, height=455");
	});
	
}

// 		   $( "div.ajaxcontent-container" ).on( "click", "#div1", function() {
// 			   console.log($( this ));
// 			 });

</script>

<?php
}
}
?>

</body>
</html>
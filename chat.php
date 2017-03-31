<?php
//CONNECTION DETAILS
define ('DBPATH','localhost:3306');
define ('DBUSER','root');
define ('DBPASS','RohNam22');
define ('DBNAME','php_db');

session_start();
//connecting to database 
global $dbh;
$dbh = mysql_connect(DBPATH,DBUSER,DBPASS);
mysql_selectdb(DBNAME,$dbh);

if ($_GET['action'] == "chatheartbeat") { chatHeartbeat(); } 
if ($_GET['action'] == "sendchat") { sendChat(); } 
if ($_GET['action'] == "closechat") { closeChat(); } 
if ($_GET['action'] == "startchatsession") { startChatSession(); } 

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

function chatHeartbeat() {
	//changing the query 
	//$frnname = sanitize($_POST['frndName']);
	$frnname = sanitize('dawa');
	//$sql = "select * from chat where (chat.to = '".mysql_real_escape_string($_SESSION['name'])."' AND recd = 0) order by id ASC";
	$username =mysql_real_escape_string($_SESSION['name']);
	echo " Friendname .'$frnname' .";
	//
	$sql = "select * from chat where chat.to in('$username','$frnname') and chat.from in('$username','$frnname') order by id desc";
	$query = mysql_query($sql);
//	$items = '';

	$chatBoxes = array();
	
	while ($chat = mysql_fetch_array($query)) {
// commented for debugging
		//if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
			$items = $_SESSION['chatHistory'][$chat['from']];
		//}
//current chat set to s:0
		$chat['message'] = sanitize($chat['message']);
		//for debugging
		$output = "<script>console.log( 'Chat message : " .$chat['message']  . "' );</script>";

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;
// EOD -It is called the HEREDOC string method, and is an alternative to using quotes for writing multiline strings.
	//current chat saved in history 
	$_SESSION['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",							
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;
		
		//unset($_SESSION['tsChatBoxes'][$chat['from']]);
		$_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
//	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	//$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo $_SESSION['name'];?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat() {
	$from = $_SESSION['name'];
	$to = $_POST['to'];
	$message = $_POST['message'];

	$_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
	
	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
		$_SESSION['chatHistory'][$_POST['to']] = '';
	}
// history is saved as 1 
	$_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "0",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$_POST['to']]);
// inserting chat in db 
	$sql = "insert into chat (chat.from,chat.to,message,sent) values ('".mysql_real_escape_string($from)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."',NOW())";
	$query = mysql_query($sql);
	echo "1";
	exit(0);
}

function closeChat() {

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}


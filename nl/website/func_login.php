<?php

function must_be_logged_in() {
//	Kovoks doesn't support secure connections at the moment.
//	if ( ! $_SERVER['HTTPS'] == 'on') {
//		header("Location: https://".$_SERVER['HTTP_HOST']
//                          .dirname($_SERVER['PHP_SELF'])
//                          .$_SERVER['PHP_SELF']);
//		exit;
//	}

	// this function checks if you must login.
	// and if so, forces you to login, or back off.
	if(!isset($GLOBALS["PHP_AUTH_USER"])) {
		Header("WWW-Authenticate: Basic realm=\"GNOME-NL\"");
		Header("HTTP/1.0 401 Unauthorized");
		echo "You must login.\n";
		echo "You can use \"gnome-nl\" as a username.\n";
		echo "And \"gnome-nl\" as a password.\n";
		echo "Your IP-address and software specs will be logged.\n";
		echo "This is to prevent abuse.\n";
		exit;
	} else {
		$md5pass = md5($GLOBALS["PHP_AUTH_PW"]);
		$username = $GLOBALS["PHP_AUTH_USER"];
		if (correctpass($username, $md5pass)) {
			session_register("username");
			session_register("md5pass"); 
		} else {
			Header("WWW-Authenticate: Basic realm=\"GNOME-NL\"");
			Header("HTTP/1.0 401 Unauthorized");
			echo "Wrong password or username entered.\n";
			exit;
		}
	}
}

function is_logged_in() {
	// are you actually logged in or not?
	$checkpass = md5($GLOBALS["PHP_AUTH_PW"]);
	$checkuser = $GLOBALS["PHP_AUTH_USER"];
/*	if (isset($GLOBALS["PHP_AUTH_USER"])) {
		if (correctpass($checkuser, $checkpass)) {
			$username = $checkuser;
			$md5pass = $checkpass;
			session_register("username");
			session_register("md5pass"); 
			return true;
		} else {
			session_unregister("username");
			session_unregister("md5pass"); 
			return false;
		}
	} else {
		return (session_is_registered("username") &&
			 session_is_registered("md5pass") &&
			 correctpass($GLOBALS["username"], $GLOBALS["md5pass"]));
	}*/
	return true;
}

function correctpass ($user, $pass) {
	$auth = false; // Assume user is not authenticated

	$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
	mysql_select_db("gnome_nl",$db);

	// build the query, order by newest ID and limit it to 10
	$sql = "select * from users where name = '".$user."'";
	$res = mysql_query($sql);
	$useritem = mysql_fetch_assoc($res);

        if ( ( $useritem["name"] == "$user" ) &&
             ( $useritem["password"] == "$pass" ) ) {

            // A match is found, meaning the user is authenticated.
            // Stop the search.

            $auth = true;

        }
//	For now, just accept all connections
//	return $auth;
	return true;
}

?>

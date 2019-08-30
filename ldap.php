<?php

session_start();
error_reporting(0);

$server_address = "";

function chk_user( $uid, $pwd ) {
	if ($pwd) {
		$ds = ldap_connect($server_address);
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		$a = ldap_search($ds, "dc=iiita,dc=ac,dc=in", "uid=$uid" );
		$b = ldap_get_entries( $ds, $a );
		$dn = $b[0]["dn"];
		$ldapbind=@ldap_bind($ds, $dn, $pwd);
		if ($ldapbind) {
			return 1;
		} else {
			return 0;
		}
		ldap_close($ds);
	} else {
		return 0;
	}
}
		
function get_name($uid) {
    $ds=ldap_connect($server_adress);
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ($ds)
    {
        $bnd=ldap_bind($ds);
        $srch=ldap_search($ds, "dc=iiita,dc=ac,dc=in", "uid={$uid}");
        $info=ldap_get_entries($ds, $srch);
        ldap_close($ds);
        $userdn=$info[0]["dn"];
        $usernm=$info[0]["cn"][0];
        return $info[0]["cn"][0];
    } else {
        return "Not available";
    }
}

	$true=chk_user($user, $pass);     
	$data = "";
	
	if($true){
		$name=get_name($user);
		$arr = explode("-",$name);
		$fname1=substr($name, 0, strrpos($name, "-"));
		$fname=str_replace("-", " ", $fname1);
		$_SESSION['name']=$name;
		$_SESSION['fname']=$fname;
		$_SESSION['user']=$user;
		$new="";
		$data = $user;
	}
	else{
		$data = "invalid credentials";
	}
	
	echo $data;
?>
<?php


class Connect2LDAP
{
	public  $ldapserver=ldapserver;
	public  $ldapport=ldapport;

	public  $basedn=basedn;
	public  $basepass=basepass;



function __construct()	{

}

function connect2LDAPServer($server,$port){
		
	echo "Connecting ...";
		$ds=ldap_connect($server,$port);  // must be a valid LDAP server!
	echo "Connect result is " . $ds . "<br />";
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		return $ds;

	
}
function bind2LDAPServer($ds,$basedn,$basepass){

	echo "Binding ..."; 
		$ldaprdn  = $basedn;    // ldap rdn or dn
		$ldappass = $basepass;  // associated password
	 	$r= ldap_bind($ds, $ldaprdn, $ldappass);

	 // verify binding
	   if ($r) {
	       echo "LDAP bind successful...";
	   } else {
	       echo "LDAP bind failed...";
	   }	

}

function searchLDAPDirectory($ds,$searchdn,$filter)
{
		    // Search surname entry //uid=* 
	    $sr=ldap_search($ds, $searchdn, $filter);  
	    echo "Search result is " . $sr . "<br />";

	    echo "Number of entires returned is " . ldap_count_entries($ds, $sr) . "<br />";

	    echo "Getting entries ...<p>";
	    $info = ldap_get_entries($ds, $sr);
	    echo "Data for " . $info["count"] . " items returned:<p>";

	    for ($i=0; $i<$info["count"]; $i++) {
		echo "dn is: " . $info[$i]["dn"] . "<br />";
		echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />";
		echo "first email entry is: " . $info[$i]["mail"][0] . "<br /><hr />";
	    }

}

function addRecord($ds,$adddn,$record){
    // add data to directory
    $r = ldap_add($ds, $adddn, $record);
	if($r)
		echo "Record added";
	else
		echo "Please check your data";

}

function deleteRecord($ds,$dn,$recursive=false){
    echo "Delete Record";
    if($recursive == false){
        return(ldap_delete($ds,$dn));
    }else{
        //searching for sub entries
        $sr=ldap_list($ds,$dn,"ObjectClass=*",array(""));
        $info = ldap_get_entries($ds, $sr);
        for($i=0;$i<$info['count'];$i++){
            //deleting recursively sub entries
            $result=myldap_delete($ds,$info[$i]['dn'],$recursive);
            if(!$result){
                //return result code, if delete fails
                return($result);
            }
        }
        return(ldap_delete($ds,$dn));
    }
}



function closeLDAPConnection($ds)
{
    echo "Closing connection";
    ldap_close($ds);
}


}

?>

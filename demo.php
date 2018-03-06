<?php
require_once(dirname(__FILE__).'/config.php');//configuration file
require_once(dirname(__FILE__).'/class.ldap.php');//ldap class file

//Initialize class file
$conobj=new Connect2LDAP();

//Connect LDAP Server
$ds=Connect2LDAP::connect2LDAPServer($conobj->ldapserver,$conobj->ldapport);

//Bind with LDAP instance
Connect2LDAP::bind2LDAPServer($ds,$conobj->basedn,$conobj->basepass);


$searchdn="ou=Group,dc=domain,dc=com";
$filter="cn=*";



//Search LDAP directory
Connect2LDAP::searchLDAPDirectory($ds,$conobj->searchdn,$conobj->filter);


// Prepare for LDAP insert data-------------


//Please change the record entry as required by your company directory structure
   $record['objectclass'][0] = "top";
   $record['objectclass'][1] = "posixAccount";
   $record['objectclass'][2] = "inetOrgPerson";

   $record["uid"]="abc";
   $record["mail"]="a@a.com";
   $record["givenName"]="abc";
   $record["sn"]="test1";
   $record["cn"]="abc";
   $record["displayName"]="abc";
   $record["userPassword"]='{md5}'. base64_encode(pack('H*', md5("111"))); //encrypt password "111" using MD5
   $record["homeDirectory"]="/home/test";
   $record["gidNumber"]="31048";
   $record["uidNumber"]="160908";

//LDAP insert dn
$adddn="ou=People,dc=domain,dc=com";

$newadddn="uid=abc,".$adddn;

	Connect2LDAP::addRecord($ds,$newadddn,$record);

//---------------------------------------

//Delete LDAP record
$deletedn="uid=abc,".$conobj->adddn;
	Connect2LDAP::deleteRecord($ds,$deletedn,true);

//Close LDAP Connection
	Connect2LDAP::closeLDAPConnection($ds);
?>

<?php
error_reporting(0);
/*

This scropt was made by Nikolay Yakovlev (russia).
You can made whatever you want with it.

*/


/*
REMEMBER - while you are working with PHP LDUP names of attributes, you want to get, must be in lower case registry! (small letters) 
*/

//serever connect property
$srv ="DOMAIN.COM";
$srv_domain ="DOMAIN.COM";
//user login and password from this user we read AD. make shure this user get rights to read AD
$srv_login ="USERNAME@".$srv_domain; 
$srv_password ="PASSWORD";
//find office/cabinet/room/place we want to get users from
//btw - this variable uses for place picture
$place = (@$_GET['place']);
$doscript=true; //variable to make run main part of script - while its true script will be run

switch($place){ //find place we want to see users from
case "first" :	//first place
	$dn ="OU=ou1,OU=DOMAIN,dc=DOMAIN,dc=COM";			
	break;
case "second"://second place
	$dn ="OU=ou2,OU=DOMAIN,dc=DOMAIN,dc=COM";			
	break;
	//you can add any other places if you need to
default:
	$doscript=false; //if there is no place we can find in the "case-switch" just include main_page.html (script not started)
	break;
}
//if we DON'T get place name successfully  
if (!$doscript) include "main_table.html"; //include main_table.

else if ($doscript) { //if we get place name we can run main part of script
// something like a header of the page - feel free to change it.
{
	echo "
<!DOCTYPE html> 
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<link rel='shortcut icon' href='ico.png'>
<meta charset='windows-1251/ '>
<!--yea, i know how to use css files, but IE, in my case, can't correctly work with it. so i had to do like this... sorry. -->
<style>
	*{
		text-align: center;
		font-family:tahoma;
		font-size:14px;}
	a{
		text-decoration: none;
		color: #000;}
	a:hover{
		text-decoration: underline;
		color: #0059FF;}

	#bold{
		text-decoration: none;
		font-weight: 600;
		font-size:20px;}

	#table,tr,td{
		border-style:solid;
		border-width:1px;
		border-collapse:collapse;
		padding:5px;
		height:22px;
		border-color:#7d7d7d;}
	/* Нечетные строки */
		#table tbody tr:nth-child(odd){
		background: #fff;}
	/* Четные строки */
		#table tbody tr:nth-child(even){
		background: #F7F7F7;}	
	#noborder{
		border-width: 0 px;	
		border-style: none;		
	}	
	
	#sp30px{text-indent: 30px;text-align: justify;}
	#smallsize{font-family:tahoma; text-indent: 5px; text-align:left; font-size:12px;}
	
	#top {
		background: #ffffff;
		text-align: center;
		left:0;
		top:0px;
		table-layout: fixed;
		border-style:solid;
		border-width:0px;
		border-collapse:collapse;
		padding:0px;
		height:22px;
		border: 0px;
		z-index: 99999;
		display:block;
		width:80px;
		opacity: 0.6;
		filter: alpha(Opacity=60);
		height:100%;
		position:fixed;}
	#top:hover{
		background: #afafaf;
		opacity: 100;
		filter: alpha(Opacity=100);
		text-decoration: none;
		color: #000000;}
	.smalltext{
		padding-top: 1px;
		padding-bottom: 1px;
		text-align: bottom;
		font-family:tahoma;
		color: #a0a0a0;
		line-height: 7px;
		font-size: 10px;}
	.smalltext:hover{
		color: #0000ff;}		
	.transition-rotate {
		position: relative;
		z-index: 2;
		margin: 0 auto;
		padding: 5px;
		text-align: center;
		max-width: 500px;
		cursor: pointer;
		transition: 0.1s linear;}
	.transition-rotate:hover {
		-webkit-transform: rotate(-2deg);
		transform: rotate(-2deg);
		}
	#lineheight{
		text-align: left;
		line-height: 1px;
		text-decoration: none;
		font-weight: 600;
		font-size:20px;}
		
</style>
<title>Adressbook of &laquo;YourMegaCompanyName&raquo;</title>	
</head>
<body style='background-color:#ffffff;'>";
}
//header is over

//arrow "back"
echo "
<table id='top'><tr><td id='top'>
<a href='index.php?place=main' id='top' >
<br><br><br>
<img src='back_to_main.png' alt='' border='0' width='75' height='60'/>
<p>На главную</p></a>
</td></tr></table>
";
//end of arrow "back"

//search filter 
$filter ="(&(objectcategory=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"; //all users, but disabled

$ds=ldap_connect($srv);   
if ($ds) { 
    $r=ldap_bind($ds,$srv_login,$srv_password);;     
	ldap_set_option($ds,LDAP_OPT_REFERRALS, 0);
	ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3);
	
	$sr=ldap_search($ds,$dn ,$filter );   
    ldap_sort($ds,$sr, "givenname");
    $info = ldap_get_entries($ds, $sr); 

    $sr2=ldap_search($ds,$dn2 ,$filter2 );   
    $placeinfo = ldap_get_entries($ds, $sr2); 
//fields to place from AD 
$PlaceName = $placeinfo[0]["l"][0];  			// name of place
$PlaceAddres = $placeinfo[0]["street"][0];		// address of place
$PlaceMail = $placeinfo[0]["description"][0]; 	// mail of place
$PlacePhone = $placeinfo[0]["st"][0]; 			// phone of plase
//visual head of page of the place (it will be nice, i think)
echo"<table align='center' height = '80'>
	<tr><td id='noborder' rowspan = 2 width = 150><div><img src=$place.png height='60'></div></td>
		<td id='noborder' ><div id='lineheight'>". $PlaceName ."</div></td></tr>
	<tr><td id='noborder' >". $PlaceAddres ."</td></tr>
    </table>
<table align='center' id='table'>
	<tr><td width='35' bgcolor = #f0f0e4>  № </td>
	<td width='300' bgcolor = #f0f0e4> Name </td>
	<td width='250' bgcolor = #f0f0e4> E-mail </td>
	<td width='60' bgcolor = #f0f0e4> Phone </td>
	<td width='150' bgcolor = #f0f0e4> Mobile </td></tr>
	<tr><td></td><td> This place data </td><td>";
if ($PlaceMail == "-") echo "-"; // if in AD we have "-" as parameter (that means no data)
else echo "<div class='transition-rotate'><a href=mailto:" . $PlaceMail .">" . $PlaceMail ." <img src='message.png' id='noborder'></a></div>";
echo "</td><td width='150'> " . $PlacePhone ." </td><td> - </td></tr>";

//users data table
for ($i=0; $i<$info["count"];$i++) 
{ 
$UserHide = $info[$i]["physicaldeliveryofficename"][0];
//if user have "hide" in the attribute "physicaldeliveryofficename" we don't show any info about this user
if (($UserHide != 'hide')and($UserHide != 'Hide')and($UserHide != 'HIDE')) 
	{
//if user not "hide" we can collect info from AD
$UserName = $info[$i]["cn"][0];
$UserPosition = $info[$i]["title"][0]; 		// lets use it as position
$UserMail = $info[$i]["mail"][0];			//mail
	if (($UserMail == 'NoMail') or ($UserMail == 'nomail') or ($UserMail == '-') or (!$UserMail)) $UserMail = "-"; 
$UserIpPhone = $info[$i]["ipphone"][0];		//ip phone
	if (!$UserIpPhone) $UserIpPhone = "-";
$UserMobile = $info[$i]["mobile"][0];		//mobile
	if (!$UserMobile) $UserMobile = "-";
//----------------------
    echo "<tr>
	<td>". $n+=1 ."</td>
	<td> ". $UserName ."<br> <div class='smalltext'>". $UserPosition ."</div></td><td>"; //	name + position 
	if ($UserMail !='-') 
		echo "<div class='transition-rotate'><a href=mailto:'$UserMail'>$UserMail  <img src='message.png' id='noborder'></a></div>";
	else 
		echo "$UserMail";
 	echo "<td> ". $UserIpPhone ." </td><td> ". $UserMobile ." </td></tr>";
	}
}
echo "</table>";
ldap_close($ds); 
	} 
else echo "<h4>Unable to connect to LDAP server</h4>"; 
echo '<br><br><br>
</body>
</html>
';
}
?>

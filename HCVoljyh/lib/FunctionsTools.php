<?php


// This function set the new language parameters
function SwitchToNewLang($newlang) {
	if ((!isset($_SESSION['lang']))or($_SESSION['lang']!=$newlang)) { // Update lang if url lang has changed
	  $RowLanguage=LoadRow("select id,ShortCode from languages where ShortCode='".$newlang."'") ;
	  
		if (isset($RowLanguage->id)) {
	    LogStr("change to language from [".$_SESSION['lang']."] to [".$newlang."]","SwitchLanguage") ;
      $_SESSION['lang']=$RowLanguage->ShortCode ;
      $_SESSION['IdLanguage']=$RowLanguage->id ;
		}
		else {
	    LogStr("problem : ".$newlang." not found after SwitchLanguage","Bug") ;
      $_SESSION['lang']="eng" ;
      $_SESSION['IdLanguage']=0 ;
		}
	}
} // end of SwitchToNewLang



//------------------------------------------------------------------------------
// ww function will display the translation according to the code and the default language
Function ww($code, $p1=NULL, $p2=NULL, $p3=NULL, $p4=NULL, $p5=NULL, $p6=NULL, $p7=NULL, $p8=NULL, $p9=NULL, $pp10=NULL, $pp11=NULL, $pp12=NULL, $pp13=NULL) {
  global $Params ;

// If no language set default language
  if (!isset($_SESSION['IdLanguage'])) {
	  $_SESSION['lang']="eng" ; 	  
		$_SESSION['IdLanguage']=0 ; 
	}
  if ($_SESSION['lang']=="") {
	  $_SESSION['lang']="eng" ; 	  
		$_SESSION['IdLanguage']=0 ;
	}
	return(wwinlang ($code,$_SESSION['IdLanguage'], $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $pp10, $pp11, $pp12, $pp13));
} // end of ww

//------------------------------------------------------------------------------
// ww function will display the translation according to the code and the default language
Function wwinlang($code,$IdLanguage=0, $p1=NULL, $p2=NULL, $p3=NULL, $p4=NULL, $p5=NULL, $p6=NULL, $p7=NULL, $p8=NULL, $p9=NULL, $pp10=NULL, $pp11=NULL, $pp12=NULL, $pp13=NULL) {
	if ((isset($_SESSION['switchtrans'])) and ($_SESSION['switchtrans']=="on")) { // if user as choosen to build a translation list to use in AdminWords
      if (!isset($_SESSION['TranslationArray'])) {
        $_SESSION['TranslationArray']=array() ; // initialize $_SESSION['TranslationArray'] if it wasent existing yet
		  }
	    if (!in_array($code,$_SESSION['TranslationArray'])) {
		    array_push($_SESSION['TranslationArray'],$code) ;
		  } 
	}

	$res="" ;
	if (empty($code)) {
		return("Empty field \$code in ww function") ;
	}
	if (is_numeric($code)) { // case code is the idword in numeric form
	  $rr=LoadRow("select SQL_CACHE Sentence from words where id=$code") ;
		$res=nl2br(stripslashes($rr->Sentence)) ;
	}
	else { // In case the code wasnt a numeric id
		$rr=LoadRow("select  SQL_CACHE Sentence from words where code='$code' and IdLanguage='".$IdLanguage."'") ;
    $res=nl2br(stripslashes($rr->Sentence)) ;
//		echo "ww('",$code,"')=",$res,"<br>" ;
	}
	if ($res=="") { // If not found
		if (is_numeric($code)) { // id word case
		  if (HasRight("Words",$IdLanguage)) {
				$res="<b>function ww() : idword #$code missing</b>" ;
			}
			else {
				$res=$code ;
			}
			return($res) ;
		}
		else {
			$rr=LoadRow("select SQL_CACHE Sentence from words where code='$code' and IdLanguage='".$IdLanguage."'") ;
			$res=nl2br(stripslashes($rr->Sentence)) ;
			if (HasRight("Words",$IdLanguage)) {
			  $rLang=LoadRow("select * from languages where id=".$IdLanguage) ; $Language=$rLang->ShortCode ; 
				$res.="<a  target=\"_new\" href=adminwords.php?IdLanguage=".$IdLanguage."&code=$code><font size=1 color=red>click to define the word <font color=blue><font size=2>$code</font></font> in </font><b>".$Language."</b></a>" ;
			}
		}
		if (HasRight("Words",$IdLanguage)) {
		  $rLang=LoadRow("select * from languages where id=".$IdLanguage) ; $Language=$rLang->ShortCode ; 
		  $res="<a  target=\"_new\" href=adminwords.php?IdLanguage=".$IdLanguage."&code=$code><font size=1 color=red>click to define the word <font color=blue><font size=2>$code</font></font> in </font><b>".$Language."</b></a>" ;
		}
		else {
		  if ($_SESSION['forcewordcodelink']==1) $res="<a  target=\"_new\" href=adminwords.php?IdLanguage=".$IdLanguage."&code=$code><font size=1 color=red>click to define the word <font color=blue><font size=2>$code</font></font> </font></a>" ;
		  else $res=$code ;
		}
//		$res="<a href=adminwords.php?search_lang=fr&search=$str&generate=check>click here to define $str</a>"
	} // else  If not found
  
	// Apply the parameters if any
	$res=sprintf($res,$p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13) ;
//	debug("code=<font color=red>".$code."</font> IdLanguage=".$IdLanguage."<br> res=[<b>".$res."</b>]");
	return ($res) ;
} // end of wwinlang


//------------------------------------------------------------------------------
function IsAdmin() {
  return (HasRight('Admin')) ;
} // end of IsAdmin()


//------------------------------------------------------------------------------
// Just to read one row
//------------------------------------------------------------------------------
function LoadRow($str) {
//  echo "str=$str<br>" ;
	$qry=mysql_query($str) ; 
	if (!$qry) {
		if ((IsAdmin()) or ($_SERVER['SERVER_NAME']=='localhost')) {
			echo "<br><font color=red>Warning message for Admin (only)<br>" ;
			debug ($_SERVER['PHP_SELF']."<br> : LoadRow error [".mysql_error()."]for <b>[".$str."]</b></font>") ;
		}
		else {
			error_log("LoadRow error in ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']." <br> str=[".$str."]<br>") ;
//			LogStrTmp("LoadRow(".addslashes($str).") in ".$_SERVER['PHP_SELF'],"Debug") ; // No need already done by hc_mysl_query
		}
		$row=$str;
	}
	else{
		$row=mysql_fetch_object($qry) ;
	}
	return($row) ;
}




//------------------------------------------------------------------------------
function LogVisit() {
  if (!isset($_SESSION['idvisitor'])) {
	  $idtext="Agent=[".$_SERVER['HTTP_USER_AGENT']."] lang=[".$_SERVER['HTTP_ACCEPT_LANGUAGE']."]" ;
		$intip=ip2long( $_SERVER['REMOTE_ADDR']) ;
		$rr=LoadRow("select * from visites where ip=".$intip." and idtext='".addslashes($idtext)."'") ;
		if ($rr) {
		  $_SESSION['idvisitor']=$rr->id ;
			LogStr("Nouvelle Identification, Nouvelle session","log") ;
		}
		else {
		  $HTTP_REFERER=$_SERVER['HTTP_REFERER'] ;
		  $qry=sql_query("insert into visites(ip,idtext,HTTP_REFERER) values($intip,'".addslashes($idtext)."','".$HTTP_REFERER."')") ;
		  $_SESSION['idvisitor']=mysql_insert_id() ;
			LogStr("Identification retrouv�e, Nouvelle session","log") ;
		}

	}
} // end of LogVisit

//------------------------------------------------------------------------------
function LogStr($stext,$stype="Log") {
//  if (!isset($_SESSION['IdMember'])) LogVisit() ;
  if (isset($_SESSION['IdMember'])) $IdMember=$_SESSION['IdMember'] ;
	else $IdMember=0 ; // Zeromember if no member in session
	if (isset($_SERVER['REMOTE_ADDR'])) $ip= $_SERVER['REMOTE_ADDR'] ;
	else $ip="128.0.0.1" ; // case its local host 
	$str="insert into logs(IpAddress,IdMember,Str,Type) values(".ip2long($ip).",".$IdMember.",'".addslashes($stext)."','".$stype."')" ;
  $qry=mysql_query($str);
	if (!$qry) {
  	if (IsAdmin()) echo "problem : LogStr \$str=$str<br>" ;
  }
} // end of LogStr


// -----------------------------------------------------------------------------
// Test if member as requested to change language
$newlang="" ;
if ((isset($_GET['lang'])) and ($_GET['lang']!='')) {
  SwitchToNewLang($_GET['lang']) ;
}
else if ((isset($_POST['lang'])) and ($_POST['lang']!='')) {
  SwitchToNewLang($_POST['lang']) ;
}
if (!isset($_SESSION['lang'])) {
  SwitchToNewLang("eng") ;
}	

// -----------------------------------------------------------------------------
// test if member use the switchtrans switch to record use of words on its page 
if ((isset($_GET['switchtrans'])) and ($_GET['switchtrans']!="")) {
  if (!isset($_SESSION['switchtrans'])) {
	  $_SESSION['switchtrans']="on" ;
	}
	else {
	  if ($_SESSION['switchtrans']=="on") {
	    $_SESSION['switchtrans']="off" ;
		}
		else {
	    $_SESSION['switchtrans']="on" ;
		}
	}
} // end of switchtrans

if (isset($_GET['forcewordcodelink'])) { // use to force a linj to each word 
                                         //code on display
  $_SESSION['forcewordcodelink']=$_GET['forcewordcodelink'] ;
}

// end of Test if member as requested to change language
// -----------------------------------------------------------------------------





// -----------------------------------------------------------------------------
// return true is the member is logged
function IsLogged() {

  if (!isset($_SESSION['IdMember']) or ($_SESSION['IdMember']==0)) {
	  return(false) ;
	}

  if ((!isset($_SESSION['MemberCryptKey'])) or ($_SESSION['MemberCryptKey']=="") ) {
	  LogStr("IsLogged() : Anomaly with MemberCryptKey","Bug") ;
	  return(false) ;
	}

	if ($_SESSION['LogCheck']!=Crc32($_SESSION['MemberCryptKey'].$_SESSION['IdMember'])) {
	  LogStr("Anomaly with Log Check","Hacking") ;
		require_once("login.php") ;
		Logout() ;
		exit(0) ;
	}
	return(true) ;
} // end of IsLogged

// -----------------------------------------------------------------------------
// the trad corresponding to the current language of the user, or english, 
// or the one the member has set
function FindTrad($IdTrad) {

// Try default language
  $row=LoadRow("select Sentence from memberstrads where IdTrad=".$IdTrad." and IdLanguage=".$_SESSION['IdLanguage']) ;
	if (isset($row->Sentence)) {
	  if (isset($row->Sentence)=="") {
		  LogStr("Blank Sentence for language ".$_SESSION['IdLanguage']." with MembersTrads.IdTrad=".$IdTrad,"Bug") ;
		}
		else {
		  return($row->Sentence) ;
		}
	}
// Try default eng
  $row=LoadRow("select Sentence from memberstrads where IdTrad=".$IdTrad." and IdLanguage=1") ;
	if (isset($row->Sentence)) {
	  if (isset($row->Sentence)=="") {
		  LogStr("Blank Sentence for language 1 (eng) with memberstrads.IdTrad=".$IdTrad,"Bug") ;
		}
		else {
		  return($row->Sentence) ;
		}
	}
// Try first language available
  $row=LoadRow("select Sentence from memberstrads where IdTrad=".$IdTrad." order by id asc limit 1") ;
	if (isset($row->Sentence)) {
	  if (isset($row->Sentence)=="") {
		  LogStr("Blank Sentence (any language) memberstrads.IdTrad=".$IdTrad,"Bug") ;
		}
		else {
		  return($row->Sentence) ;
		}
	}
	return("Empty MembersTrads for IdTrad=".$IdTrad)  ;
} // end of FindTrad

// -----------------------------------------------------------------------------
// return the RightLevel if the members has the Right RightName 
// optional Scope value can be send if the RightScope is set to All then Scope
// will alawys match if not, the sentence in Scope must be find in RightScope
// The function will use a cache in session
//   $_SYSHCVOL['ReloadRight']=='True' is used to force RightsReloading
//  fro scope beware to the "" which must exist in the mysal table but NOT in 
// the $Scope parameter 
// $OptionalIdMember  allow to specify another member than the current one, in this case the cache is not used
function HasRight($RightName,$Scope="",$OptionalIdMember=0) {
  if (!isset($_SESSION['IdMember'])) return(0) ; // No need to search for right if no member logged
	if ($OptionalIdMember!=0) {
    $IdMember=$OptionalIdMember ;
	}
	else {
    if (($_SESSION["IdMember"])==1) return (10) ; // Admin has all rights at level 10
    $IdMember=$_SESSION['IdMember'] ;
	}

  if ((!isset($_SESSION['Right_'.$RightName]))or ($_SYSHCVOL['ReloadRight']=='True')or($OptionalIdMember!=0)) {
	  $str="select Scope,Level from rightsvolunteers,rights where IdMember=$IdMember and rights.id=rightsvolunteers.IdRight and rights.Name='$RightName'" ;
    $qry=mysql_query($str) or die("function HasRight : Sql error for ".$str) ;
	  $right=mysql_fetch_object(mysql_query($str)) ; // LoadRow not possible because of recusivity
		if (!isset($right->Level)) return(0) ; // Return false if the Right does'nt exist for this member in the DB
    $rlevel=$right->Level ;
    $rscope=$right->Scope ;
		if ($OptionalIdMember==0) { // if its current member cache for next research 
	    $_SESSION['RightLevel_'.$RightName]=$rlevel ;
	    $_SESSION['RightScope_'.$RightName]=$rscope ;
		}
	}
	if ($Scope!="") { // if a specific scope is asked
	  if ($rscope=="\"All\"") {
	    return($rlevel) ;
	  }
	  else {
	    if (strpos($rscope,"\"".$RightScope."\"")===true)  {
			  return($rlevel) ;
			}
		  else return(0) ;
		} 
	}
	else {
	  return($rlevel) ;
	}
} // enf of HasRight

// -----------------------------------------------------------------------------
// return the Scope in the specific right 
// The funsction will use a cache in session
//   $_SYSHCVOL['ReloadRight']=='True' is used to force RightsReloading
//  fro scope beware to the "" which must exist in the mysal table but NOT in 
// the $Scope parameter 
function RightScope($RightName,$Scope="") {
  if (!isset($_SESSION['IdMember'])) return(0) ; // No ned to search for right if no member logged
  $IdMember=$_SESSION['IdMember'] ;
  if ((!isset($_SESSION['Right_'.$RightName]))or ($_SYSHCVOL['ReloadRight']=='True')) {
	  $str="select Scope,Level from rightsvolunteers,rights where IdMember=$IdMember and rights.id=rightsvolunteers.IdRight and rights.Name='$RightName'" ;
    $qry=mysql_query($str) or die("function HasRight : Sql error for ".$str) ;
	  $right=mysql_fetch_object(mysql_query($str)) ; // LoadRow not possible because of recusivity
		if (!isset($right->Level)) return(0) ; // Return false if the Right does'nt exist for this member in the DB 
	  $_SESSION['RightLevel_'.$RightName]=$right->Level ;
	  $_SESSION['RightScope_'.$RightName]=$right->Scope ;
	}
	return($_SESSION['RightScope_'.$RightName]) ;
} // enf of Scope

//------------------------------------------------------------------------------
function getcountryname($IdCountry) {
  $rr=LoadRow("select Name from countries where id=".$IdCountry) ;
	return ($rr->Name) ;
}
 
//------------------------------------------------------------------------------
function ProposeCountry($Id=0) {
  $ss="" ;
	$str="select id,Name from countries order by Name" ;
	$qry=sql_query($str) ;
	$ss="\n<select name=IdCountry onChange=\"change_country();\">\n" ;
	while ($rr=mysql_fetch_object($qry)) {
	  $ss.="<option value=".$rr->id ;
		if ($rr->id==$Id) $ss.=" selected" ;
		$ss.=">" ;
		$ss.=$rr->Name ;
//			if ($rr->OtherNames!="")	$ss.=" (".$rr->OtherNames.")" ;
		$ss.="</option>\n" ;
	}
	$ss.="\n</select>\n" ;
		
	return($ss) ;
} // end of ProposeCountry

//------------------------------------------------------------------------------
function ProposeRegion($Id=0,$IdCountry=0) {
  if ($IdCountry==0) {
	  return ("\n<input type=hidden name=IdRegion Value=0>\n") ;
	}
  $ss="" ;
	$str="select id,Name,OtherNames from regions where IdCountry=".$IdCountry." order by Name" ;
	$qry=sql_query($str) ;
	$ss="\n<select name=IdRegion onChange=\"change_region()\">\n" ;
	while ($rr=mysql_fetch_object($qry)) {
	  $ss.="<option value=".$rr->id ;
		if ($rr->id==$Id) $ss.=" selected" ;
		$ss.=">" ;
		$ss.=$rr->Name ;
//		if ($rr->OtherNames!="")	$ss.=" (".$rr->OtherNames.")" ;
		$ss.="</option>\n" ;
	}
	$ss.="\n</select>\n" ;
		
	return($ss) ;
} // end of ProposeRegion
//------------------------------------------------------------------------------
function ProposeCity($Id=0,$IdRegion=0) {
  if ($IdRegion==0) {
	  return ("\n<input type=hidden name=IdCity Value=0>\n") ;
		return("") ;
	}
  $ss="" ;
	$str="select id,Name,OtherNames from cities where IdRegion=".$IdRegion." order by Name" ;
	$qry=sql_query($str) ;
	$ss="\n<select name=IdCity>\n" ;
	while ($rr=mysql_fetch_object($qry)) {
	  $ss.="<option value=".$rr->id ;
		if ($rr->id==$Id) $ss.=" selected" ;
		$ss.=">" ;
		$ss.=$rr->Name ;
//		if ($rr->OtherNames!="")	$ss.=" (".$rr->OtherNames.")" ;
		$ss.="</option>\n" ;
	}
	$ss.="\n</select>\n" ;
		
	return($ss) ;
} // end of ProposeCity

//------------------------------------------------------------------------------
// CheckEmail return true if the email looks valid
function CheckEmail($email) {
  if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
		'@'.
		'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
		'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
		   return(false) ;
			 
  }
	else {
    return(true) ; // email ok
	}

}


// -----------------------------------------------------------------------------
// hc_mail is a function to centralise all mail send thru HC 
function hvol_mail($to,$the_subject,$text,$hh="",$_FromParam="",$IdLanguage=0,$PreferenceHtmlEmail="",$LogInfo="",$replyto="") {
  return hcvol_sendmail($to,$the_subject,$text,"",$hh,$FromParam,$IdLanguage,$PreferenceHtmlEmail="",$LogInfo="",$replyto) ;
}


// -----------------------------------------------------------------------------
// hcvol_sendmail is a function to centralise all mail send thru HC with more feature 
// $to = email of receiver
// $mail_subject=subject of mail
// $text = text of mail
// $textinhtml = text in html will be use if user preference are html
// $From= from mail (will also be the reply to)
// $deflanguage : d�fault language of receiver
// $PreferenceHtmlEmail : if set to yes member will receive mail in html format, note that it will be force to html if text contain ";&#"
// $LogInfo = used for debugging

function hcvol_sendmail($to,$mail_subject,$text,$textinhtml="",$hh="",$_FromParam="",$IdLanguage=0,$PreferenceHtmlEmail="",$LogInfo="",$replyto="") {
  global $_SYSHCVOL ;
	$verbose=false ;
//  $verbose=1; // set to one for a verbose function
  $FromParam=$_FromParam ;
  if ($_FromParam=="") $FromParam=$_SYSHCVOL['MessageSenderMail'] ;

	$From=$FromParam ;

	$text=str_replace("<br />","",$text) ;
	
//	nl2br_inv($text) ;	// neutralize the nl2br() of ww() and wwinlang()
	$text=str_replace("\r\n","\n",$text) ; // solving the century-bug: NO MORE DAMN TOO MANY BLANK LINES!!!

	$use_html=$PreferenceHtmlEmail ;
  if ($verbose) echo "<br>use_html=[".$use_html."] mail to $to<br>\n\$_SERVER['SERVER_NAME']=",$_SERVER['SERVER_NAME'],"<br>\n";
	if (stristr($text,";&#")!=false) { // if there is any non ascii file, force html
    if ($verbose) echo "<br>1 <br>\n";
		if ($use_html!="yes") {
      if ($verbose) echo "<br>2<br>\n";
			$use_html="yes" ;
			if ($LogInfo=="") {
				LogStr("Forcing HTML for message to $to","hcvol_mail") ;
			}
			else {
				LogStr("Forcing HTML <b>$LogInfo</b>","hchcvol_mail") ;
			}
		}
	}

	$headers = $hh;
	if (($use_html=="yes")or(strpos($text,"<html>")!==false)) { // if html is forced or text is in html then add the MIME header
  if ($verbose) echo "<br>3<br>";
		if ((ord($headers{0})==13)and(ord($headers{1})==10)) { // case a terminator is allready set
			echo "stripping \\r and \\n<br>\n" ;
			$headers .= "MIME-Version: 1.0\r\nContent-type: text/html; charset=\"iso-8859-1\"".$headers;
		}
		else {
			$headers = "MIME-Version: 1.0\nContent-type: text/html; charset=\"iso-8859-1\"\n";
			$headers .= "X-Sender:<$From>\n";
			$headers .= "X-Mailer:PHP\n".$hh; // mail of client			
		}
		$use_html="yes" ;
	}

	if ($replyto!="") {
		$headers=$headers."Reply-To:".$replyto."\r\n" ;
	}
	if (!(strstr($headers,"From:"))and($From!="")) {
		$headers=$headers."From:".$From."\r\n" ;
	}
	if (!(strstr($headers,"Reply-To:"))and($From!="")) {
		$headers=$headers."Reply-To:".$From."\r\n" ;
	}
	elseif (!strstr($headers,"Reply-To:")) {
		$headers=$headers."Reply-To:".$_SYSHCVOL['MessageSenderMail']."\r\n" ;
	}

//	$headers.="To: $to\r\n";
//	$headers.="Subject: $mail_subject\r\n";
//	$headers.="Return-Path: $From\r\n";


	$headers=$headers."Organization: ".$_SYSHCVOL['SiteName'] ;
	
	if ($use_html=="yes") {
    if ($verbose) echo "<br>4<br>\n";
		if ($textinhtml!="") { 
    if ($verbose) echo "<br>5 will use text in html paramameter<br>";
			$texttosend=$textinhtml ;
		}
		else {
      if ($verbose) echo "<br>6<br>\n";
			$texttosend=$text ;
		}
		if (strpos($texttosend,"<html>")===false) { // If not allready html
    if ($verbose) echo "<br>7<br>";
			$realtext="<html><head><title>".$mail_subject."</title></head><body bgcolor=#ffffcc>".str_replace("\n","<br>",$texttosend).
			$realtext.="<br><font color=blue>".wwinlang('HCVolMailSignature',$IdLanguage)."</font>" ;
			$realtext.="</body></html>" ;
		}
		else {
      if ($verbose) echo "<br>8<br>\n";
			$realtext=$texttosend ; // In this case, its already in html
		}
	}
	else {
  if ($verbose) echo "<br>9 <br>\n";
		$text.="\n".wwinlang('HCVolMailSignature',$IdLanguage) ;
		$realtext=str_replace("<br>","\n",$text) ;
	}

  if ($verbose) echo "<br>10 ".nl2br($realtext)."<br>\n" ;

  if ($verbose) echo "<br>11 ".nl2br($realtext)."<br>\n" ;
  if ($verbose) echo "<br>12 ".$realtext."<br>\n" ;

// Debugging trick	
	if ($verbose) {   
		echo "<table bgcolor=#ffff99 cellspacing=3 cellpadding=3 border=2><tr><td>" ;
		echo "\$From:<font color=#6633ff>$From</font> \$To:<font color=#6633ff>$to</font><br>" ;
		echo "\$mail_subject:<font color=#6633ff><b>",$mail_subject,"</b></font></td>" ;
		$ss=$headers;
		echo "<tr><td>\$headers=<font color=#ff9933>" ;
		for ($ii=0;$ii<strlen($ss);$ii++) {
//			echo "\$ss[$ii]=",ord($ss{$ii})," [",$ss{$ii},"]<br>" ;
			$jj=ord($ss{$ii}) ;
			if ($jj==10) {
				echo "\\n<br>" ;
			}
			elseif ($jj==13) {
				echo "\\r" ;
			}
			else {
				echo chr($jj) ;
			}
		}
		echo "</font></td>"  ;
		echo "<tr><td><font color=#6633ff>",htmlentities($realtext),"</font></td>" ;
		if ($use_html=="yes") echo "<tr><td>$realtext</td>" ;
		echo "</table><br>" ;
	} // end of for $ii
// end of debugging trick

// remove new line in $mail_subject because it is not accepted
  if ($verbose) echo "<br>13 removing extra \\n from \$mail_subject<br>\n" ;
  for ($ii=0;$ii<strlen($mail_subject);$ii++) {
//	  echo $ii,"-->",$mail_subject{$ii}," ",ord($mail_subject{$ii}),"<br>" ; ;
	  if ((ord($mail_subject{$ii})<32)or(ord($mail_subject{$ii})>127)) {
		  $mail_subject{$ii}=" " ;
			echo "One weird char removed in subject at ",$ii," position<br>\n";
		} 
	}

		
		
  if ($_SERVER['SERVER_NAME']=='localhost') { // Localhost don't send mail
	  return("<br><b><font color=blue>".$mail_subject."</font></b><br><b><font color=blue>".$realtext."</font></b><br>"." not sent<br>");
	}
  elseif (($_SERVER['SERVER_NAME']=='ns20516.ovh.net')or(($_SERVER['SERVER_NAME']=='www.hcvolunteers.org'))or(($_SERVER['SERVER_NAME']=='www.bewelcome.org'))) {
	  $ret=mail($to,$mail_subject,$realtext,$headers,"-".$_SYSHCVOL['ferrorsSenderMail'])  ;
    if ($verbose) {
		  echo "<br>14 <br>\n" ;
			echo "headers:\n";
			print_r($headers) ;
			echo "\n<br>to=",$to,"<br>\n" ;
			echo "subj=",$mail_subject,"<br>" ;
			echo "text :<i>",htmlentities($realtext),"</i><br>\n" ;
			echo" \$ret=",$ret,"<br>\n" ;
		}
//		echo "Mail sent to $to<br>" ;
		return($ret) ;
	}
} // end of hcvol_sendmail


//------------------------------------------------------------------------------
//
function debug($s1="",$s2="",$s3="",$s4="",$s5="",$s6="",$s7="",$s8="",$s9="",$s10="",$s11="",$s12="") {
  debug_print_backtrace() ;
	echo  $s1.$s2.$s3.$s4.$s5.$s6.$s7.$s8.$s9.$s10.$s11.$s12."<br>" ;
}


//------------------------------------------------------------------------------
// InsertInCrypted allow to insert a string in Crypted table
// It returns the ID of the created record 
function InsertInCrypted($ss,$_IdMember="",$IsCrypted="crypted") {
  if ($ss=="") return (0) ; // Dont create a crypted data for a void value
  if ($_IdMember=="") { // by default it is current member
	  $IdMember=$_SESSION['IdMember'] ;
	}
	else {
	  $IdMember=$_IdMember ;
	}
	
	$str="insert into cryptedfields(AdminCryptedValue,MemberCryptedValue,IdMember,IsCrypted) values(\"".$ss."\",\"".$ss."\",".$IdMember.",\"".$IsCrypted."\")" ;
	sql_query($str)  ;
	return(mysql_insert_id()) ;
} // end of InsertInCrypted

//------------------------------------------------------------------------------
// MemberCrypt allow a member to Crypt his crypted data
function MemberCrypt($IdCrypt) {
	$IdMember=$_SESSION['IdMember'] ;
	$str="update  cryptedfields set IsCrypted='crypted' where IsCrypted='not crypted' and IdMember=".$IdMember." and id=".$IdCrypt ;
	sql_query($str) ;
} // end of MemberCrypt

//------------------------------------------------------------------------------
// MemberDecrypt allow a member to Crypt his crypted data
function MemberDecrypt($IdCrypt) {
	$IdMember=$_SESSION['IdMember'] ;
	$str="update  cryptedfields set IsCrypted='not crypted' where IsCrypted='crypted' and IdMember=".$IdMember." and id=".$IdCrypt ;
	sql_query($str) ;
} // end of MemberDecrypt

//------------------------------------------------------------------------------
// IsCrypted return true if data is crypted
function IsCrypted($IdCrypt) {
  if ($IdCrypt==0) return (false) ; // if no value, it is not crypted
	$IdMember=$_SESSION['IdMember'] ;
  $rr=LoadRow("select * from cryptedfields where id=".$IdCrypt) ;
	switch ($rr->IsCrypted) {
	  case "not crypted" :
		  return(false) ;
	  case "crypted" :
		  return(true) ;
	  case "always" :
		  return(true) ;
		default:
		  return(true) ;
		
	}
} // end of IsCrypted


//------------------------------------------------------------------------------
// AdminReadCrypted read the crypt field
// todo : complete this function
function AdminReadCrypted($IdCrypt) {
  // todo limit to right decrypt or similar
	$IdMember=$_SESSION['IdMember'] ;
  $rr=LoadRow("select * from cryptedfields where id=".$IdCrypt) ;
	return($rr->AdminCryptedValue) ;
} // end of AdminReadCrypted

//------------------------------------------------------------------------------
// PublicReadCrypted read the crypt field
// return the plain text if contend is not crypted
// If not return standard "is crypted text"
// todo : complete this function
// if memberdata is crypted, return standard word cryptedhidden or content of optional parameter $returnval 
function PublicReadCrypted($IdCrypt,$returnval="") {
	$IdMember=$_SESSION['IdMember'] ;
  $rr=LoadRow("select * from cryptedfields where id=".$IdCrypt) ;
	if ($rr->IsCrypted=="not crypted") {
	  return($rr->MemberCryptedValue) ;
	}
  if ($rr->MemberCryptedValue=="") return("") ; // if empty no need to send crypted	
  if ($returnval=="") return(ww("cryptedhidden")) ;
	else return($returnval) ;
} // end of PublicReadCrypted


//------------------------------------------------------------------------------
// MemberReadCrypted read the crypt field
// return the plain text if the current member is the owner of the crypted object
// If not return standard "is crypted text"
// todo : complete this function
function MemberReadCrypted($IdCrypt) {
  if ($IdCrypt==0) return("") ; // if 0 it mean that the field is empty 
  $rr=LoadRow("select * from cryptedfields where id=".$IdCrypt) ;
	if ($_SESSION["IdMember"]==$rr->IdMember) {
//	  echo $rr->MemberCryptedValue,"<br>" ;
	  return($rr->MemberCryptedValue) ;
	}
	else {
    if ($rr->MemberCryptedValue=="") return("") ; // if empty no need to send crypted	
	  return(ww("cryptedhidden")) ;
	}
} // end of MemberReadCrypted


//------------------------------------------------------------------------------
// ReverseCrypt  return "decrypt" if $IdCrypt correspond to a crypt field
//               return "crypt" if $IdCrypt correspond to a not crypted field
function ReverseCrypt($IdCrypt) {
  if (IsCrypted($IdCrypt))  return "decrypt" ;
	else return "crypt" ;
}


//------------------------------------------------------------------------------
// InsertInMTrad allow to insert a string in MemberTrad table
// It returns the IdTrad of the created record 
function InsertInMTrad($ss,$_IdMember=0,$_IdLanguage=-1,$IdTrad=-1) {
  if ($_IdMember==0) { // by default it is current member
	  $IdMember=$_SESSION['IdMember'] ;
	}
	else {
	  $IdMember=$_IdMember ;
	}

	if ($_IdLanguage==-1) $IdLanguage=$_SESSION['IdLanguage'] ;
	else $IdLanguage=$_IdLanguage ;

	if ($IdTrad==-1) { // if a new IdTrad is needed
  // Compute a new IdTrad
	  $rr=LoadRow("select max(IdTrad) as maxi from memberstrads") ;
	  if (isset($rr->maxi)) { 
	    $IdTrad=$rr->maxi+1 ;
	  }
	  else {
	    $IdTrad=1 ;
	  }
	}
	
	$IdOwner=$IdMember ;
	$IdTranslator=$_SESSION['IdMember'] ; // the recorded translator will always be the current logged member
	$Sentence=$ss ;
	$str="insert into memberstrads(IdLanguage,IdOwner,IdTrad,IdTranslator,Sentence,created) " ; 
	$str.="Values(".$IdLanguage.",".$IdOwner.",".$IdTrad.",".$IdTranslator.",\"".$Sentence."\",now())" ;
	sql_query($str)  ;
//	echo "::InsertInMTrad IdTrad=",$IdTrad," str=",$str,"<hr>" ;
	return($IdTrad) ;
} // end of InsertInMTrad

//------------------------------------------------------------------------------
// ReplaceInMTrad insert or replace the value corresponding to $IdTrad in member Trad
// if ($IdTrad==0) then a new record is inserted
// It returns the IdTrad of the created record 
function ReplaceInMTrad($ss,$IdTrad=0,$IdOwner=0) {
  if ($IdOwner==0) {
	  $IdMember=$_SESSION['IdMember'] ;
	}
	else {
	  $IdMember=$IdOwner ;
	}
//  echo "in ReplaceInMTrad \$ss=[".$ss."] \$IdTrad=",$IdTrad," \$IdOwner=",$IdMember,"<br>" ;
	$IdLanguage=$_SESSION['IdLanguage'] ;
	if ($IdTrad==0) {
	  return(InsertInMTrad($ss,$IdMember)) ; // Create a full new translation
	}
	$IdTranslator=$_SESSION['IdMember'] ; // the recorded translator will always be the current logged member
	$str="select * from memberstrads where IdTrad=".$IdTrad." and IdOwner=".$IdMember." and IdLanguage=".$IdLanguage ;
	$rr=LoadRow($str) ;
	if (!isset($rr->id)) {
//	  echo "[$str] not found so inserted <br>" ;
	  return(InsertInMTrad($ss,$IdMember,$IdLanguage,$IdTrad)) ; // just insert a new record in memberstrads in this new language
	}
	else {
	  if ($ss!=addslashes($rr->Sentence)) { // Update only if sentence has changed
	    MakeRevision($rr->id,"memberstrads") ; // create revision
	    $str="update memberstrads set IdTranslator=".$IdTranslator.",Sentence='".$ss."' where id=".$rr->id ;
	    sql_query($str) ;
		}
	}
	return($IdTrad) ;
} // end of ReplaceInMTrad

//------------------------------------------------------------------------------
// ReplaceInCrypted allow to replcae astring in Crypted table
// It returns the ID of the replaced record 
function ReplaceInCrypted($ss,$IdCrypt,$_IdMember=0,$IsCrypted="crypted") {
  if ($_IdMember==0) { // by default it is current member
	  $IdMember=$_SESSION['IdMember'] ;
	}
	else {
	  $IdMember=$_IdMember ;
	}
	if ($IdCrypt==0) {
	  return(InsertInCrypted($ss,$IdMember,$IsCrypted)) ; // Create a full new crypt record
	}
	else {
	  $rr=LoadRow("select * from cryptedfields where id=".$IdCrypt) ;
		if (!isset($rr->id)) { // if no record exist
	    return(InsertInCrypted($ss,$IdMember,$IsCrypted)) ; // Create a full new crypt record
		}
	}

	// todo : manage cryptation, manage IdMember when it is not the owner of the record (in this case he must have the proper right)
	
	$str="update cryptedfields set IsCrypted=\"".$IsCrypted."\",AdminCryptedValue=\"".$ss."\",MemberCryptedValue=\"".$ss."\" where id=".$rr->id." and IdMember=".$rr->IdMember ;
	sql_query($str)  ;
	return($IdCrypt) ;
} // end of ReplaceInCrypted


// 
// mysql_get_set returns in an array the possible set values of the colum of table name
function mysql_get_set($table,$column) {
    $sql = "SHOW COLUMNS FROM $table LIKE '$column'";
    if (!($ret = sql_query($sql)))
        die("Error: Could not show columns $column");

    $line = mysql_fetch_assoc($ret);
    $set  = $line['Type'];
    $set  = substr($set,5,strlen($set)-7); // Remove "set(" at start and ");" at end
    return preg_split("/','/",$set); // Split into and array
} // end of mysql_get_set($table,$column) 
// 
// mysql_get_enum returns in an array the possible set values of the colum of table name
function mysql_get_enum($table,$column) {
    $sql = "SHOW COLUMNS FROM $table LIKE '$column'";
    if (!($ret = sql_query($sql)))
        die("Error: Could not show columns $column");

    $line = mysql_fetch_assoc($ret);
    $set  = $line['Type'];
    $set  = substr($set,6,strlen($set)-8); // Remove "enum(" at start and ");" at end
    return preg_split("/','/",$set); // Split into and array
} // end of mysql_get_enum($table,$column) 

//------------------------------------------------------------------------------ 
// Get param returns the param value (in get or post) if any
function GetParam($param,$defaultvalue="") {
  if (isset($_GET[$param])) {
    return($_GET[$param]) ;
  }
  if (isset($_POST[$param])) {
    return($_POST[$param]) ;
  }
	return($defaultvalue) ; // Return defaultvalue if none
} // end of GetParam


// 
// sql query execute a mysql_query but logs errors if any, and 
// dummp on screen if member has right Debug
function sql_query($ss_sql) {
  if ($_SESSION['sql_query']=="AlreadyIn") {
//	  die ("<br>recursive sql_query<br>".$ss_sql) ;
	}
	$_SESSION['sql_query']="AlreadyIn" ;
  $qry=mysql_query($ss_sql) ;
	if ($qry) {
	  $_SESSION['sql_query']="" ;
		return($qry) ;
	}
  if ((HasRight("Debug"))or($_SERVER['SERVER_NAME']=='localhost')or(1)) {
	  $_SESSION['sql_query']="" ;
		die(debug("<br>query problem with<br><font color=red>".$ss_sql."</font><br>")) ;
	}
  LogStr("Pb with <b>".$ss_sql."</b>","sql_query") ;
	die("query problem ".$_SERVER['REMOTE_ADDR']." ".date("F j, Y, g:i a")) ;
} // end of sql_query


//------------------------------------------------------------------------------ 
// function EvaluateMyEvents()  evaluate several events :
// - not read message
function EvaluateMyEvents() {
  global $_SYSHCVOL ;
  if (!isset($_SESSION["IdMember"])) return ; // if member not identified, no evaluation needed
	if ($_SYSHCVOL['EvaluateEventMessageReceived']=="Yes") {
	  $IdMember=$_SESSION['IdMember'] ;
		$str="select count(*) as cnt from messages where IdReceiver=".$IdMember." and WhenFirstRead='0000-00-00 00:00:00' and Status='Sent'";
//		echo "str=$str<br>" ;
	  $rr=LoadRow($str) ;
		
    $_SESSION['NbNotRead']=$rr->cnt ;
	} else {
    $_SESSION['NbNotRead']=0 ;
	} 
	
	if ($_SYSHCVOL['WhoIsOnlineActive']=="Yes") { // Keep upto date who is online if it is active
	  $str="replace into online set IdMember=".$IdMember.",appearance='".fUsername($IdMember)."',lastactivity='".$_SERVER["PHP_SELF"]."'" ;
		sql_query($str) ;
	  $rr=LoadRow("select count(*) as cnt from online where online.updated>DATE_SUB(now(),interval ".$_SYSHCVOL['WhoIsOnlineDelayInMinutes']." minute) ") ; 
	  $_SESSION['WhoIsOnlineCount']=$rr->cnt ;	
	}
	else {
	  $_SESSION['WhoIsOnlineCount']="###" ; // Not activated
	}	
	return ;
} // end of EvaluateMyEvents()

//------------------------------------------------------------------------------ 
// function LinkWithUsername build a link with Username to the member profile 
// optional parameter status can be used to alter the link
function LinkWithUsername($Username,$Status="") {
  return ("<a href=\"member.php?cid=$Username\">$Username</a>") ;
} // end of LinkWithUsername

//------------------------------------------------------------------------------ 
// function CreateKey compute a nearly unique key according to parameters 
function CreateKey($s1,$s2,$IdMember="",$ss="default")  {
  $key=sprintf("%X",crc32($s1." ".$s2." ".$IdMember."_".$ss)) ; // compute a nearly unique key
	return($key) ;
} // end of CreateKey


//------------------------------------------------------------------------------ 
// function LinkEditWord display a link to edit the word $code in language $IdLanguage
// if $ll is not specified then default language will be used  
function LinkEditWord($code,$_IdLanguage=-1) {
  $IdLanguage=$_IdLanguage ;
  if ($IdLanguage==-1) {
	  $IdLanguage=$_SESSION["IdLanguage"] ;
	}
	$str="<a href=\"adminwords.php?IdLanguage=".$IdLanguage."&code=$code\">edit</a>" ;
	return($str) ;
} // end of LinkEditWord


//------------------------------------------------------------------------------ 
// function IdMember return the id of the member according to its username
function IdMember($username) {
  $rr=LoadRow("select id from members where username='".$username."'") ;
	if (isset($rr->id)) {
	  return($rr->id) ;
	}
	return(0) ;
} // end of IdMember

//------------------------------------------------------------------------------ 
// function fUsername return the Username of the member according to its id
function fUsername($cid) {
  if (!is_numeric($cid)) return ($cid) ; // If cid is not numeric it is assumed to be already a username
  $rr=LoadRow("select username from members where id=".$cid) ;
	if (isset($rr->username)) {
	  return($rr->username) ;
	}
	return("") ;
} // end of fUsername


//------------------------------------------------------------------------------
// MakeRevision this function save a copy of current value of record Id in table
// TableName for member IdMember with DoneBy reason
function MakeRevision($Id,$TableName,$IdMemberParam=0,$DoneBy="DoneByMember") {
  $IdMember=$IdMemberParam ;
	if ($IdMember==0) $IdMember=$_SESSION["IdMember"] ;
  $qry=sql_query("select * from ".$TableName." where id=".$Id) ;
	$count=mysql_num_fields($qry) ;
	$rr=mysql_fetch_object($qry) ;

  $XMLstr="" ;
	for ($ii=0;$ii<$count;$ii++) {
	  $field=mysql_field_name($qry,$ii) ;
    $XMLstr.="<field>".$field."</field>\n" ;
    $XMLstr.="<value>".$rr->$field."</value>\n" ;
	} 
	$str="insert into previousversion(IdMember,TableName,IdInTable,XmlOldVersion,Type) values(".$IdMember.",'".$TableName."',".$Id.",'".addslashes($XMLstr)."','".$DoneBy."')" ;
	sql_query($str) ;
} // end of MakeRevision

//------------------------------------------------------------------------------
// Local date return the local date according to preference
// parameter $tt is a timestamp
function localdate($ttparam,$formatparam="") {
// todo apply local offset to $tt
  $tt=strtotime($ttparam) ;
  $format=$formatparam ;
 if ($format=="") {
   $format="%c" ;
 }
 return(strftime($format,$tt)) ;
} // end of localdate

//------------------------------------------------------------------------------
// fage return a string describing the age correcponding to date 
function fage($dd,$hidden="No") {
  if ($hidden!="No") {
	  return(ww("AgeHidden")) ;
	}
	return(ww("AgeEqualX",fage_value($dd))) ;
} // end of fage

//------------------------------------------------------------------------------
// fage_value return a  the age value corresponding to date 
function fage_value($dd) {
	$iDate=strtotime($dd) ;
	$age=(time()-$iDate)/(365*24*60*60) ;
	return($age) ;
} // end of fage_value


//------------------------------------------------------------------------------
// function fFullName return the FullName of the member with a special layout if some fields are crypted 
function fFullName($m) {
  return(PublicReadCrypted($m->FirstName,"*")." ".PublicReadCrypted($m->SecondName,"*")." ".strtoupper(PublicReadCrypted($m->LastName,"*"))) ;
} // end of fFullName


//------------------------------------------------------------------------------
// function GetDefaultLanguage return the default language of member $IdMember 
function GetDefaultLanguage($IdMember) {
  $def=0 ; // default to english
	$rr=LoadRow("select Value from memberspreferences where IdPreference=1 and IdMember=".$IdMember) ;
	if (isset($rr->Value)) $def=$rr->Value ;
	return($def) ;
} // end of GetDefaultLanguage

//------------------------------------------------------------------------------
// function GetEmail return the email of member $IdMember (or current member if 0) 
function GetEmail($IdMemb=0) {
  if ($IdMemb==0) $IdMember=$_SESSION["IdMember"] ;
	else $IdMember=$IdMemb ; 
	$rr=LoadRow("select Email from members where id=".$IdMember) ;
	if ($rr->Email>0) return(AdminReadCrypted($rr->Email)) ;
	else  return "" ;
} // end of GetEmail


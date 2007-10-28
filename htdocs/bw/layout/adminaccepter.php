<?php
/*

Copyright (c) 2007 BeVolunteer

This file is part of BW Rox.

BW Rox is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

BW Rox is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/> or 
write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, 
Boston, MA  02111-1307, USA.

*/


require_once ("menus.php");

// this function retruns the number of time a pending members has been renotified
function CountNotify($Username) {
	global $_SYSHCVOL ;
	$str="select count(*) as cnt from ".$_SYSHCVOL['ARCH_DB'].".logs where Type='resendconfirmyourmail' and Str like '%<b>".$Username."</b>%'" ;
//	echo "str=$str" ; 
	$rr=LoadRow($str) ;
	return($rr->cnt) ;
} // end of CountNotify


function ShowList($TData,$bgcolor="white",$title="") {
	global $global_count;
	$max = count($TData);
	$count = 0;
	
	if ($title!="") echo "              <p class=\"highlight\">\n",("$max"),$title," \n";
	for ($ii = 0; $ii < $max; $ii++) {
		$m = $TData[$ii];
		$count++;
		echo "              \n";
		$info_styles = array(0 => "          <div class=\"info\">\n", 1 => "          <div class=\" info highlight\">\n");
		echo $info_styles[($ii%2)];
		echo "             <input type=hidden name=IdMember_".$global_count." value=".$m->id.">\n";
		echo "             <p> <font size=5>",LinkWithUsername($m->Username),"</font> (",ww($m->Gender),")", " (",fsince($m->created)," ",localdate($m->created),")</p>\n";
		echo "             <p> <font size=4>",$m->FirstName," <i>",$m->SecondName,"</i> <b>",$m->LastName,"</b> </font>(<i>",$m->Email,"</i>)</p>\n";
       echo "          <h4>", ww('ProfileSummary'), "</h4>\n";	
		echo "          <p>", $m->ProfileSummary, "</p\n";
		echo "             <h4>", ww('Address'), "</h4>\n";
		echo "             <ul>\n";
		echo "               <li>", $m->HouseNumber, ", ", $m->StreetName, "</li>\n";
		echo "               <li>", $m->Zip, " ", $m->cityname, "</li>\n";
		echo "               <li>", $m->regionname, "</li>\n";
  	echo "               <li>", $m->countryname, "</li>\n";
  	echo "             </ul>\n";
  	echo "            <br />\n";
	if ($m->FeedBack!="") echo "             <p>Feedback : <font color=green><b><i>", str_replace("\n","<br>",$m->FeedBack), "</i></b></font></p>\n";
		echo "             <p>\n";
		if ($m->Status == "Pending")
		   echo "               <input type=radio name=action_".$global_count." value=accept> accept<br>\n";
		   echo "               <input type=radio name=action_".$global_count." value=reject> reject<br>\n";
		if ($m->Status == "Pending") {
		   echo "               <input type=radio name=action_".$global_count." value=needmore> need more<br>\n";
		}
	  echo "               <input type=radio name=action_".$global_count." value=nothing> nothing<br>\n";
		echo "             </p>\n";
		echo "             <p>";
		if ($m->Status == "Pending") {
		   echo "needmore aditional text for emailing to member<br>";
		   echo "                <textarea name=needmoretext_".$global_count." cols=60 rows=4>";
		   echo "</textarea>\n";
		}
		echo "</p>\n";
		  
		echo "             <ul class=\"linkist\">";
		if ($m->Status == "MailToConfirm") {
			 echo "              <li><a href=\"../resendconfirmyourmail.php?Username=".$m->Username."\" onclick=\"return('Confirm you want to send again ? (beware not to spam members !) ');\">Send request for confirmation mail again</a>" ;
			 $countnotify=CountNotify($m->Username) ;
			 if ($countnotify==0) {
			 	  echo "<i> never re-notified </i>" ;
			 }
			 else {
			 	  echo "<b> already re-notified ",$countnotify," time</b>" ;
			 }
			 echo "</li>" ;
		}
		echo "               <li><a href=\"".bwlink("contactmember.php?cid=". $m->id). "\">contact</a></li>\n";
		echo "               <li><a href=\"".bwlink("updatemandatory.php?cid=". $m->id). "\">update mandatory</a></li>\n";
		echo "             </ul>\n";
		echo "           </div>\n";
		$global_count++;
	}
	echo "        <div class=\"info\">\n";
	echo "          <p>Total", $count, "</p>\n";
} // end of ShowList

function DisplayAdminAccepter($TData,$TNeedMore, $lastaction = "") {
	global $countmatch;
	global $title;
	global $global_count;
	$title = "Accept members";
	global $AccepterScope;

	$Status=GetStrParam("Status","Pending") ;
	
	$global_count=0 ;

	include "header.php";

	Menu1("", ww('MainPage')); // Displays the top menu

	Menu2("admin/adminaccepter.php", ww('MainPage')); // Displays the second menu

	DisplayHeaderShortUserContent($title . " : " . $lastaction);
	
   echo "          <div class=\"info\">\n";
	echo "            <p>your Scope : ", $AccepterScope, "</p>\n";
	echo "          </div>\n";
	
//	if (!IsAdmin()) {
//	  echo "temporarly disabled, under test";
//	  include "footer.php";
//	}


	$tt=sql_get_enum("members","Status") ;
	$filterstatus="          <select name=Status>\n";
	for ($ii=0;$ii<count($tt);$ii++) {
	  	$filterstatus.="          <option value=\"".$tt[$ii]."\"";
	   	if ($tt[$ii]==$Status) $filterstatus.=" selected" ;
	  	$filterstatus.=">$tt[$ii]</option>\n";
	}	
	$filterstatus.="          </select>  <input type=submit id=submit name=submit></p>\n" ;
	
	echo "          <form name=adminaccepter action=".bwlink("admin/adminaccepter.php").">\n";

  ShowList($TData,"#ffff66"," Members with status ".$filterstatus);
  echo "<div style=\"text-align: center\"><input type=submit id=submit name=submit></div></div>\n";

	echo "<input type=hidden name=action value=batchaccept>";
	echo "<input type=hidden name=global_count value=$global_count>";
	echo "          </form>\n";
	

	include "footer.php";
} // end of DisplayAdminAccepter($Taccepted,$Tmailchecking,$Tpending)

<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
if ($CURUSER){
	begin_blockT("Tema");

	$ss_r = SQL_Query_exec("SELECT * from stylesheets");
	$ss_sa = array();

	while ($ss_a = mysql_fetch_assoc($ss_r)){
		$ss_id = $ss_a["id"];
		$ss_name = $ss_a["name"];
		$ss_sa[$ss_name] = $ss_id;
	}

	ksort($ss_sa);
	reset($ss_sa);
    
	while (list($ss_name, $ss_id) = each($ss_sa)){
		if ($ss_id == $CURUSER["stylesheet"]) $ss = " selected='selected'"; else $ss = "";
		$stylesheets .= "<option value='$ss_id'$ss>$ss_name</option>\n";
	}

	$lang_r = SQL_Query_exec("SELECT * from languages");
	$lang_sa = array();

	while ($lang_a = mysql_fetch_assoc($lang_r)){
		$lang_id = $lang_a["id"];
		$lang_name = $lang_a["name"];
		$lang_sa[$lang_name] = $lang_id;
	}

	ksort($lang_sa);
	reset($lang_sa);

	while (list($lang_name, $lang_id) = each($lang_sa)){
		if ($lang_id == $CURUSER["language"]) $lang = " selected='selected'"; else $lang = "";
		$languages .= "<option value='$lang_id'$lang>$lang_name</option>\n";
	}

?>
 
 <form method="post" action="take-theme.php">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
<td align="center" valign="middle"><b>Tema</b>
<select name="stylesheet"><?php echo $stylesheets; ?></select></td>
  </tr>

  <tr>
<td align="center" valign="middle"><input type="submit" value="Alterar" /></td>
  </tr>
</table>
  </form>  

<?php
end_block();
}
?>
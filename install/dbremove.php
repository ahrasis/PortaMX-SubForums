<?php
/****************************** 
* file dbremove.php           *
* Database tables remove      *
* Coypright by PortaMx corp.  *
*******************************/

global $db_prefix, $user_info, $boardurl, $txt;

// Load the SSI.php
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	function _write($string) { echo $string; }

	require_once(dirname(__FILE__) . '/SSI.php');

	// on manual installation you have to logged in
	if(!$user_info['is_admin']) 
	{
		if($user_info['is_guest']) 
		{
			echo '<b>', $txt['admin_login'],':</b><br />';
			ssi_login($boardurl.'/dbinstall.php');
			die();
		}
		else 
		{
			loadLanguage('Errors');
			fatal_error($txt['cannot_admin_forum']);
		}
	}
}
// no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> SSI.php not found. Please verify you put this in the same place as SMF\'s index.php.');
else
{
	function _write($string) { return; };
}

// split of dbname (mostly for SSI)
$pref = explode('.', $db_prefix);
if(!empty($pref[1]))
	$pref = $pref[1];
else
	$pref = $db_prefix;

// Load the SMF DB Functions
db_extend('packages');
db_extend('extra');

/********************
* Define the tables *                      
*********************/ 
$tabledate = array(
	'subforums',
);

// loop through each table
foreach($tabledate as $tblname)
{
	// check if the table exist
	_write('Processing Table "'. $pref . $tblname .'".<br />');
	$tablelist = $smcFunc['db_list_tables'](false, $pref. $tblname);
	if(!empty($tablelist) && in_array($pref . $tblname, $tablelist))
	{
		// drop table
		$smcFunc['db_drop_table']('{db_prefix}'. $tblname);
		_write('.. Table "'. $pref . $tblname .'" successful dropped.<br /><br />');
	}
	else
		_write('.. Table "'. $pref . $tblname .'" not exist.<br /></br />');
}

// done
_write('dbremove done.');
?>
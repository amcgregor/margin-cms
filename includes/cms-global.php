<?
//
// Margin-CMS Global Configuration Handler 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//

define('MCMS_VERSION', '1.0-pre2');
define('MCMS_BASEPATH', $_SERVER['MCMS_BASEPATH']);

// Include various important bits...
require("cms-functions.php");

// Register custom error handling.
error_reporting(E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
$ErrorHandler = new CMSErrors();

// Setup various global variables.
$pageStartTime = CMS::GetMicrotime();
$cmsWarnings = array();

// Read the 'margin-cms.xml' file in the configuration directory.
$cmsGlobalXML = simplexml_load_file(MCMS_BASEPATH . "/configuration/margin-cms.xml")
                  or trigger_error("Unable to load XML configuration file!", E_USER_ERROR);

// Compress each page before it is sent to the browser.
if ( CMS::IsTrue($cmsGlobalXML->General->CompressOutput) ) ob_start("ob_gzhandler");

// Optionally tidy the resulting (X)HTML of each page.
if ( CMS::IsTrue($cmsGlobalXML->General->TidyOutput) ) ob_start("CMS::ob_tidyhandlercustom");

// Some pages may take awhile to process, allow delays up to a minute.
//if ( is_numeric($cmsGlobalXML->General->PageTimeout) )
  //set_time_limit($cmsGlobalXML->General->PageTimeout);

if ( CMS::IsTrue($cmsGlobalXML->General->UseSessions) ) session_start();

if ( (bool)ini_get('register_globals') )
  trigger_error("Possible security risk - register_globals is active.", E_USER_WARNING);
 
?>
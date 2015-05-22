<?
//
// Margin-CMS Site Configuration Handler 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//

// Attempt to determine the virtual server the user requested.
// $_SERVER['HTTP_HOST'] contains the requested host name, while SERVER_NAME
// contains the actual server's name - a good fallback.

// Allow the individual page to override the default site definition.
// This is an unsupported feature and is not accessible via administration.
if ( !empty($cmsPageXML['site']) ) {
  $sourceFileName = sprintf("%s/configuration/sites/%s.xml", $_SERVER['MCMS_BASEPATH'], $cmsPageXML['site']);
} else {
  $baseServer = substr($_SERVER['HTTP_HOST'], 0, strlen(strrchr($_SERVER['HTTP_HOST'], '.')) * -1);
  $sourceFileName = sprintf("%s/configuration/sites/%s.xml", $_SERVER["MCMS_BASEPATH"], $baseServer);
}

if ( !file_exists($sourceFileName) ) {
  $baseServer = substr($_SERVER['SERVER_NAME'], 0, strlen(strrchr($_SERVER['SERVER_NAME'], '.')) * -1);
  $sourceFileName = sprintf("%s/configuration/sites/%s.xml", $_SERVER["MCMS_BASEPATH"], $baseServer);
  if ( !file_exists($sourceFileName) ) {
    echo "Unable to determine virtual server.";
    exit;
  }
}

// Read the site-wide file in the site configuration directory.
$cmsSiteXML = simplexml_load_file($sourceFileName) or die ("Unable to load Site-XML file!");

?>
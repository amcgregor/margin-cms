<?
//
// Margin-CMS Server Configuration Handler 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//

// Read the server-wide file in the server configuration directory.

$sourceFileName = sprintf("%s/configuration/servers/%s.xml", $_SERVER["MCMS_BASEPATH"], $cmsSiteXML['server']);
$cmsServerXML = simplexml_load_file($sourceFileName) or die ("Unable to load Site-XML file!");

?>
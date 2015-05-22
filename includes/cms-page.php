<?
//
// Margin-CMS Page Display Handler 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//

// Attempt to discover which file we need to read...
if ( !isset($pageSourceFileName) ) {
  //$pathData = explode("/", trim("#root" . $_SERVER['PATH_INFO'], " /"));
  //$sourceFileName = sprintf("%s/documents", $_SERVER["MCMS_BASEPATH"]);

  $pathData = explode("/", trim("#root" . $_SERVER['REQUEST_URI'], " /"));
  $sourceFileName = sprintf("%s/documents", $_SERVER["MCMS_BASEPATH"]);
  if ( $pathData[count($pathData)-1] == "#root" ) $pathData[] = "_default";
  
  array_shift($pathData);
  $sourceFileName .= sprintf("/%s", implode("/", $pathData));

  if ( is_dir($sourceFileName) ) {
    $pathData[] = "_default";
    $sourceFileName .= sprintf("/%s", $pathData[count($pathData)-1]);
  } $sourceFileName .= ".xml";
} else $sourceFileName = sprintf("%s/documents/%s", $_SERVER["MCMS_BASEPATH"], $pageSourceFileName);

// Output the correct HTTP header.
if ( !file_exists($sourceFileName) ) {
  header("HTTP/1.1 404 Not Found");
  $_SERVER['REDIRECT_STATUS'] = "404";
  $sourceFileName = sprintf("%s/documents/_404.xml", $_SERVER["MCMS_BASEPATH"]);
} else {
  header("HTTP/1.1 200 OK");
  $_SERVER['REDIRECT_STATUS'] = "200";
}

// Load the page data.
$cmsPageXML = simplexml_load_file($sourceFileName) or die ("Unable to load Page-XML file!");

?>
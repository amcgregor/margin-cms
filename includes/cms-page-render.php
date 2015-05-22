<?
//
// Margin-CMS Page Display Handler 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//

// Recurse into each child of the top-level Content element.

CMS::RecurseDocument($cmsPageXML->Content, "Document");

?>
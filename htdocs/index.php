<?
//
// Margin-CMS Request Handler 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//
// Note: After preliminary tests I have determined that simplexml is the way
// to go for XML parsing as opposed to DOMIT.  Take the following example:
//
//     A reasonable XML file (5.7 KB) was read a thousand times using both
//     PHP 5's SimpleXML system and DOMIT.  On the test system, SimpleXML
//     took a total of 0.64 seconds to process - 1560 reads per second.
//     DOMIT spent 11 seconds performing the same task - 90 reads/second.
//

require("cms-global.php");
require("cms-page.php");
require("cms-site.php");
require("cms-page-render.php");

?>
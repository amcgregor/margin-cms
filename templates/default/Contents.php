<div id="floater">
  <h2>Table of Contents</h2>
  <ol>
<? $XMLNodes = $cmsPageXML->xpath("Content/Title[@level = " . $cmsValues['level'] . "]");
   foreach ( $XMLNodes as $XMLNode ) { ?>
    <li><a href="#<?= isset($XMLNode['id']) ? $XMLNode['id'] : md5(isset($XMLNode['caption']) ? $XMLNode['caption'] : $XMLNode); ?>"><?= isset($XMLNode['caption']) ? $XMLNode['caption'] : $XMLNode; ?></a></li>
<? } ?>
  </ol>
</div>
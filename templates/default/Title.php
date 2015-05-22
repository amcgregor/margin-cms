<h<?= $cmsValues['level']; ?><?= CMS::Property($cmsValues, 'id', 'id', md5(!empty($childData) ? $childData : $cmsValues['caption'])); ?><?= CMS::Property($cmsValues, 'class'); ?>>
<?= CMS::Indent(!empty($childData) ? $childData : $cmsValues['caption']); ?>
</h<?= $cmsValues['level']; ?>>
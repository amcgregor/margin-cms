<form<?= CMS::Property($cmsValues, 'id'); ?><?= CMS::Property($cmsValues, 'class'); ?><?= CMS::Property($cmsValues, 'action'); ?><?= CMS::Property($cmsValues, 'method', 'method', 'post'); ?>>
<?= CMS::Indent($childData); ?>
</form>
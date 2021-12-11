<script language="javascript">
	var PS_WP_ROOT = '<?php echo get_bloginfo('wpurl'); ?>';
</script>

<style type="text/css">
<?php include( WP_PLUGIN_DIR . '/kadence-galleries/photoshelter/style.css');?>
</style>

<?php
require_once( WP_PLUGIN_DIR . '/kadence-galleries/photoshelter/photoshelter-psiframe.php');
echo "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>";
echo "<script type='text/javascript' src='" . get_bloginfo('wpurl') . "/wp-content/plugins/kadence-galleries/photoshelter/main.js'></script>";

global $psc;
$iframe = new KT_PSIFrame($psc);

if (!empty($_GET['page_url'])) {
	$qA = parse_url($_GET['page_url']);
	parse_str($qA['query'], $page);
	$_GET = array_merge($_GET, $page);
}

if (isset($_GET['G_ID']) && $_GET['embedGallery']){
	$iframe->embedGallery($_GET['G_ID'], $_GET['G_NAME']);
} else if (isset($_GET['embedGallery']) && $_POST['G_ID']) {
	$iframe->insertGallery($_POST['G_ID'], $_POST['G_NAME'], $_POST['G_URL']);
} else {
	$iframe->listGalleries();
}
?>

<?php if ( ! defined( 'ABSPATH' ) )
     exit;
?>

<?php 
	if($_POST['amty_hidden'] == 'Y') {
		//Form data sent
		$bulkVar = $_POST['bulk_action'];
		if($bulkVar == 1){
			amty_clearImageCacheSoft();
		}elseif($bulkVar == 2){
			amty_clearImageCacheHard();
		}elseif($bulkVar == 3){
			amty_clearImageCacheFull();
		}elseif($bulkVar == 4){
			amty_populateCache();
		}elseif($bulkVar == 5){
			amty_repopulateImageCache();
		}
		
		$singleVar = $_POST['single_action'];
		$p = $_POST['post_id'];
		if($singleVar == 1){
			amty_putIntoImageCache($p,0);
		}elseif($singleVar == 2){
			amty_putIntoImageCache($p,1);
		}elseif($singleVar == 3){
			amty_deletePostFromCache($p);
		}
	}
?>

<div class="wrap">
<center><h2>amtyThumb Admin page</h2></center>
<br>
<div>
<form name="amtyThumb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="amty_hidden" value="Y" />
	<strong>Bulk Action</strong>
	<select name="bulk_action">
		<option value="0">No action</option>
		<option value="1">Clear Image cache [soft]</option>
		<option value="2">Clear Image cache [hard]</option>
		<option value="3">Clear Image cache [full]</option>
		<option value="4">Populate Image cache for rest posts</option>
		<option value="5">Repopulate Image cache.</option>
	</select>
	<br />
	<br />
	<strong>Single Action</strong>
	<select name="single_action">
		<option value="0">No action</option>
		<option value="1">Put into cache if absent</option>
		<option value="2">Put into cache even if present</option>
		<option value="3">Delete from cache</option>
	</select>
	Post ID : <input type="text" name="post_id" />
	<br />
	<br />
	Total images cached : <?php echo amty_getImageCacheCount(); ?> <br/>
	Total thumbnails in cache : <?php echo amty_getFilesInCacheFolder(); ?>
	<p class="submit">
	<input type="submit" name="Submit" value="submit" />
	</p>
</form>
</div>
<hr />

<div>
<h1> Manual Caching </h1>
<form name="amtyThumb_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="manualcache" value="Y" />
	<p>
	Cache For : 
	<select name="action">
		<option value="0">Single Post</option>
		<option value="1">All posts</option>		
	</select>
	Post ID (optional) :  <input type="text" name="post_id" />
	Width : <input type="text" name="width" />
	, height : <input type="text" name="height" />
	, Constrain :  	<select name="constrain">
		<option value="0">0</option>
		<option value="1">1</option>		
	</select>
	, Zoom/Crop :  	<select name="zc">
		<option value="0">Zoom</option>
		<option value="1">Crop</option>		
	</select>
	<input type="submit" name="Submit" value="submit" />
	</p>
	<?php 
		if(isset($_POST['manualcache']) && $_POST['manualcache'] == 'Y') {
			if(isset($_POST['width']) && isset($_POST['height']) && isset($_POST['constrain']) && isset($_POST['zc'])) {
				if($_POST['action'] == '0') {
					if(isset($_POST['post_id'])) {
						amty_populateCacheManual($_POST['width'],$_POST['height'],$_POST['constrain'],$_POST['zc'],$_POST['post_id']);
					}else{
						echo "Please provide Post ID.";
					}
				}else{
					amty_populateCacheAllManual($_POST['width'],$_POST['height'],$_POST['constrain'],$_POST['zc']);
				}
			}else{
				echo "Please provide all required parameters.";	
			}
		}
	?>
</form>
</div>
<hr />
<div>
<h1>Test Image cache</h1>
<form name="amtyThumbShow_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="showthumb" value="Y" />
	Post ID : <input type="text" name="pid" value="<?php echo $_POST['pid'];?>"/>
	<input type="submit" name="Submit" value="submit" />
	<br />
	<?php 
		if($_POST['showthumb'] == 'Y') {
			amty_displayThumb($_POST['pid']);
		}
	?>
</form>
</div>
<hr />
<div>
<h1>Test Plugin </h1>

	<form name="amtyThumbTest_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="testplugin" value="Y" />
		Mode : <select name="mode"><option value="0">Image URL </option><option value="1">Post Id </option></select>
		Image URL/Post ID : <input type="text" name="post_id" value="<?php echo $_POST['post_id'];?>" style="width:250px"/>
		<br />
		If you don't specify percent then specify width and height both.
		<br />
		Percent (only numeric) : <input type="text" name="percent" value="<?php echo $_POST['percent'];?>" style="width:50px"/> |
		Width : <input type="text" name="width" value="<?php echo $_POST['width'];?>" style="width:50px"/>
		Height : <input type="text" name="height" value="<?php echo $_POST['height'];?>" style="width:50px"/>
		<br />
		if you specify Constrain as 0, resized image will be stretched. To maintain the ratio keep it 1.
		<br />
		Zoom/Crop :  <select name="zc"><option value="0">0</option><option value="1">1</option></select>
		Constrain : <select name="cons"><option value="0">0</option><option value="1">1</option></select>
		<center><p><input type="submit" name="Submit" value="submit" /></p></center>
		
		<?php 
			if($_POST['testplugin'] == 'Y') {
				if($_POST['mode'] == 0){
					amty_testPlugin($_POST['post_id'],'',$_POST['width'],$_POST['height'],$_POST['percent'],$_POST['cons'],$_POST['zc']);
				}
				else{
					amty_testPlugin('',$_POST['post_id'],$_POST['width'],$_POST['height'],$_POST['percent'],$_POST['cons'],$_POST['zc']);
				}
			}
		?>
	</form>
</div>
<hr />
<div>
<h1>Report Broken but cached Links</h1>
<form name="amtyThumbReport_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="reportBroken" value="Y" />
	<center><p><input type="submit" name="reportBrokenSubmit" value="Report Broken Cached Images" /></p></center>
	<?php 
		if($_POST['reportBroken'] == 'Y') {
			reportBrokenImage();
		}
	?>
</form>
</div>
</div>
<hr />

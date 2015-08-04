<?php global $formID; ?>
<div class='bt-search-container <?php if(isset($args['class'])) echo $args['class']; ?>' id="<?php echo $formID; ?>" >
	<form class="bt-advance-search" action="<?php echo site_url(); ?>" method="get" role="search" >
		<div> 
			<label class="btsearch-label"><?php echo __('Search','janothemes'); ?></label>
			<div class="bt-search-field-container" >
				<input  type="text" name="s" value="<?php echo get_search_query() ?>" 
						id="bt-search-field" class="bt-search-field"
						Placeholder="<?php echo (isset($args['placeholder'])) ? $args['placeholder'] :''; ?>"
						autocomplete="off"
				/><button class="bt-search-submit icon-search-2"><?php  __('Search','janothemes'); ?></button>
				<span class="btsearch-loader "> </span>
				<span class="icon-cancel-2 btsearch-cross "> </span>
				<div class="bt-search-result-container"></div>
				<?php
				$post_types = (isset($args['posttype'])) ? $args['posttype'] : 'post';
				$post_types = explode(",",$post_types);
				if(count($post_types) > 1) {
				foreach($post_types as $post_type){
				?> 
				<input type="hidden" name="post_type[]" value="<?php echo $post_type; ?>"  />
				<?php } 
				} else {
				?>
				<input type="hidden" name="post_type" value="<?php  echo $post_types[0]; ?>"  />
				<?php
				}
				?>
			</div>
		</div>
	</form>
	<?php if(isset($args['loader'])) {?>
	<img src="<?php echo BTSEARCH_URL."/assets/img/".$args['loader'].".GIF"; ?>" style="display:none"  /> 
   <?php } ?>
</div>
<script >
jQuery(document).ready(function() {
	jQuery('#<?php echo $formID; ?> input[name="s"].bt-search-field').btsearch_autocomplete({
		formID : "<?php echo $formID; ?>",
		minChar : "<?php echo (isset($args['minchar'])) ? $args['minchar'] : 1; ?>",
		resultContainerWidth : "<?php echo (isset($args['resultcontainerwidth'])) ? $args['resultcontainerwidth'] : false; ?>",
		perPage : "<?php echo (isset($args['perpage']) && $args['perpage'] > 0) ? $args['perpage'] : 5; ?>",
		loaderImg : "<?php echo (isset($args['loaderimg']) && $args['loaderimg'] != '') ? $args['loaderimg'] : false; ?>",
		loader : "<?php echo (isset($args['loader'])) ? $args['loader'] : false; ?>",
		postType : "<?php echo (isset($args['posttype'])) ? $args['posttype'] : 'post'; ?>",
		siteurl : "<?php echo site_url(); ?>",
		view_all : "<?php echo __('View All','janothemes'); ?>"
	
	});
});
</script>


	
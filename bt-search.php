<?php
class BTsearch {

 public function __construct() {
 
 	add_filter('widget_text', 'do_shortcode');
	
 	add_shortcode( 'bt_search_form', array($this, 'bt_add_search_form') );
	
	add_action( 'wp_enqueue_scripts', array($this, 'bt_load_scripts') );
	
	add_action( 'wp_ajax_btsearch_action', array($this, 'btsearch_action_callback') ); 
	
	add_action('wp_ajax_nopriv_btsearch_action', array($this, 'btsearch_action_callback'));
	
	add_action( 'init', array($this, 'btsearch_textdomain' ));
	
 }

 public function bt_load_scripts() {

	wp_enqueue_style( 'btsearch-style', BTSEARCH_URL . '/assets/css/btsearch-style.css' ,'', '',false);
	
	wp_enqueue_style( 'btsearch-fontello-style', BTSEARCH_URL . '/assets/css/fontello.css' ,'', '',false);
			
	wp_enqueue_script( 'btsearch-script', BTSEARCH_URL . '/assets/js/auto-complete.js', array('jquery'), '1.0' );
			
 }
 
 public function btsearch_action_callback() { 

	global $wpdb;
	
	$s = esc_attr($_REQUEST['keyword']);
	
	$per_page = $_REQUEST['perPage'];
	
	$post_type = $_REQUEST['postType'];
	
	$meta_query = '';
			
	$meta_where = '';
	
	if(isset($post_type) && $post_type == 'product' ) {
	
		$meta_query = " INNER JOIN {$wpdb->prefix}postmeta  AS pm  ON pm.post_id= {$wpdb->prefix}posts.ID ";
				
		$meta_where = " AND pm.meta_key='_visibility' and pm.meta_value IN( 'search', 'visible' ) ";
	
	}
	
	 if ( $post_type == 'any' ) {
				
			$posttypes = get_post_types( array(
				 'publicly_queryable' => true,
				'show_ui' => true 
			), 'objects' );
			
			$post_type = '';
			
			$i = 0;
			foreach ( $posttypes as $posttype ) {
				
				if ( $i > 0 ) {
					
					$post_type .= "," . $posttype->name;
					
				} else {
					
					$post_type .= $posttype->name;
				}
				$i++;
			}
			
		}
			
		$s = apply_filters( 'btsearch_keyword', $s );
			
		$per_page = apply_filters( 'btsearch_posts_per_page', $per_page );
			
		$sql = "SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts
			" . $meta_query . "	 
			WHERE 1=1 AND 
			((({$wpdb->prefix}posts.post_title LIKE '%" . $s . "%') OR 
			({$wpdb->prefix}posts.post_content LIKE '%" . $s . "%'))) AND 
			
			{$wpdb->prefix}posts.post_type IN( '" . implode( "','", explode( ",", $post_type ) ) . "' ) AND 
			({$wpdb->prefix}posts.post_status = 'publish')
			" . $meta_where . " 
			ORDER BY {$wpdb->prefix}posts.post_title LIKE '%" . $s . "%' DESC,
			{$wpdb->prefix}posts.post_date DESC
			LIMIT 0, " . $per_page;
		
		$search_results = $wpdb->get_results( $sql );

		 if(!empty($search_results)) { 

			$posts =  $search_results;
			
			$suggestions = array();
			
			foreach($posts as $post) {
			
				$post = (array) $post;
				
			    $newpost = array();
				
				$newpost['ID'] = $post['ID'];
				
				$newpost['permalink'] = get_permalink( $post['ID'] );
				
				$newpost['post_title'] = $post['post_title'];
				
				$suggestions[] = $newpost; 
			}
		 
		 } else {
		 	
			$suggestions[] = array(

                    'ID'    => 0,

                    'value' => __( 'Nothing Found...', 'janothemes' ),

                );
			
		 }
		 
	echo json_encode( $suggestions );
	
	wp_die(); 
	
   }


   public function bt_add_search_form($args) {

		ob_start();
		
		global $formID;
		
		$formID = uniqid('formid');
		
		search_form($args);
		
		return ob_get_clean();
		
}

 public function btsearch_textdomain() {
 
   load_plugin_textdomain( 'janothemes', '', PLUGIN_BASENAME.'/languages' ); 
   
 }

 public function process_terms($terms) {
 
 	$cats = array();
	
	if( $terms != false ) { 
	
		foreach($terms as $term) {
		
			$cats[] = "<span id='term-".$term->term_id."'>".$term->name."</span>";
			
		}
		
		return implode(", ", $cats);
		
	}
	
	return false;
	
 }

}
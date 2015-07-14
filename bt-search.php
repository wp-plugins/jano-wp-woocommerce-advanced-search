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
			
	wp_enqueue_script( 'btsearch-script', BTSEARCH_URL . '/assets/js/auto-complete.js', array('jquery'), '1.0', true );
			
 }
 
 public function btsearch_action_callback() { 

	global $wpdb;
	
	$s = esc_attr($_REQUEST['keyword']);
	
	$per_page = $_REQUEST['perPage'];
	
	$post_type = $_REQUEST['postType'];
	
	$meta_query = array();
	
	if(isset($post_type) && $post_type == 'product' ) {
	
		$price = (isset($_REQUEST['productPrice']) && $_REQUEST['productPrice'] == true) ? true : false;
		
		$meta_query = array(

                        'key'     => '_visibility',

                        'value'   => array( 'search', 'visible' ),

                        'compare' => 'IN'

                    );
		
	}
	
	 $args = array(
	 
                's'                   => apply_filters( 'btsearch_keyword', $s ),

                'post_type'           => explode(",",$post_type),

                'post_status'         => 'publish',

                'orderby'             => 'title',

                'order'               => 'ASC',

                'posts_per_page'      => apply_filters( 'btsearch_posts_per_page', $per_page ),
				
				'ignore_sticky_posts' => 1,

                'meta_query'          => array( $meta_query )

            );
			
		 $search_results = get_posts( $args );

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
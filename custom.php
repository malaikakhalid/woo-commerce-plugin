<?php
/*
* Plugin Name: Custom 
* Plugin URI: 
* Author: Malaika Khalid
* Author URI:
* Description: custom shopping System
*/
function my_custom_post_product() {
  $args = array();
  register_post_type( 'product', $args ); 
}
add_action( 'init', 'my_custom_post_product' );

function my_custom_post_productt() {
  $labels = array(
    'name'               => _x( 'Products', 'post type general name' ),
    'singular_name'      => _x( 'Product', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'book' ),
    'add_new_item'       => __( 'Add New Product' ),
    'edit_item'          => __( 'Edit Product' ),
    'new_item'           => __( 'New Product' ),
    'all_items'          => __( 'All Products' ),
    'view_item'          => __( 'View Product' ),
    'search_items'       => __( 'Search Products' ),
    'not_found'          => __( 'No products found' ),
    'not_found_in_trash' => __( 'No products found in the Trash' ), 
    'menu_name'          => 'Products'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our products and product specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 
      'title', // post title
     'editor', // post content
    'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
),
    'has_archive'   => true,
    // 'rewrite' => array('slug' => 'shop'),
  );
  register_post_type( 'product', $args ); 
}
add_action( 'init', 'my_custom_post_productt' );




function my_taxonomies_product() {
  $args = array();
  register_taxonomy( 'product_category', 'product', $args );
}

add_action( 'init', 'my_taxonomies_product', 0 );

function my_taxonomies_productt() {
  $labels = array(
    'name'              => _x( 'Product Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Product Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Product Categories' ),
    'all_items'         => __( 'All Product Categories' ),
    'parent_item'       => __( 'Parent Product Category' ),
    'parent_item_colon' => __( 'Parent Product Category:' ),
    'edit_item'         => __( 'Edit Product Category' ), 
    'update_item'       => __( 'Update Product Category' ),
    'add_new_item'      => __( 'Add New Product Category' ),
    'new_item_name'     => __( 'New Product Category' ),
   
    'menu_name'         => __( 'Product Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
    'taxonomies' => array('post_tag'),
  );
  register_taxonomy( 'product_category', 'product', $args);
}
add_action( 'init', 'my_taxonomies_productt', 0 );



add_action( 'init', 'create_tag_taxonomies', 0 );

//create two taxonomies, genres and tags for the post type "tag"

function create_tag_taxonomies() 
{
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tags' ),
    'popular_items' => __( 'Popular Tags' ),
    'all_items' => __( 'All Tags' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Tag' ), 
    'update_item' => __( 'Update Tag' ),
    'add_new_item' => __( 'Add New Tag' ),
    'new_item_name' => __( 'New Tag Name' ),
   
    'separate_items_with_commas' => __( 'Separate tags with commas' ),
    'add_or_remove_items' => __( 'Add or remove tags' ),
    'choose_from_most_used' => __( 'Choose from the most used tags' ),
    'menu_name' => __( 'Product Tags' ),
  ); 

  register_taxonomy('tag','product',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
     'taxonomies' => array('post_tag'),
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag' ),
  ));
}


add_action( 'load-post-new.php', 'hcf_register_meta_boxes' );

function hcf_register_meta_boxes() {
    add_meta_box( 'smashing-post-class',      // Unique ID
    esc_html__( 'Product Attribute', 'example' ),    // Title
    'hcf_display_callback',   // Callback function
    'product',         // Admin page (or post type)
    'side',         // Context
    'default'   );
}
add_action( 'add_meta_boxes', 'hcf_register_meta_boxes' );



function hcf_display_callback( $post ) {
 include plugin_dir_path( __FILE__ ) . './form.php';
}


function hcf_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'hcf_sku',
        'hcf_price',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'hcf_save_meta_box' );



// function get_the_category( $post_id = false ) {
//     $categories = get_the_terms( $post_id, 'category' );
//     if ( ! $categories || is_wp_error( $categories ) ) {
//         $categories = array();
//     }
 
//     $categories = array_values( $categories );
 
//     foreach ( array_keys( $categories ) as $key ) {
//         _make_cat_compat( $categories[ $key ] );
//     }
 
   
//     return apply_filters( 'get_the_categories', $categories, $post_id );
// }



// function get_the_categorys( $post_id = false ) {
// 	$categories = get_the_terms( $post_id, 'category' );
// 	if ( ! $categories || is_wp_error( $categories ) ) {
// 		$categories = array();
// 	}

// 	$categories = array_values( $categories );

// 	foreach ( array_keys( $categories ) as $key ) {
// 		_make_cat_compat( $categories[ $key ] );
// 	}

	
// 	return apply_filters( 'get_the_categories', $categories, $post_id );
// }







// function wpa_category_template( $templates = '' ){
//     $special_categories = array(
//         'accessories',
//         'Jewels',
//         'uncategories'
//     );
//     $this_category = get_queried_object();
//     if( in_array( $this_category->slug, $special_categories ) ){
//         $templates = locate_template( array( '../../themes/lana-blog/template-prod.php', $templates ), false );
//     }   
//     return $templates;
// }
// add_filter( 'category_template', 'wpa_category_template' );


// function mySearchFilter($query) {
//     // $post_type = $_GET['post_type'];
//     $category = $_GET['category'];
//     if (!$post_type) {
//         $post_type = 'any';
//     }
//     if (!$category){
//         $category = null;
//     }
//     if ($query->is_search) {
//         // $query->set('post_type', $post_type);
//         $query->set('category', $category);
//     };
//     return $query;
// };


// add_filter('pre_get_posts','mySearchFilter');



add_shortcode("show-products", "get_all_products");

function get_all_products($attr){
$products_type = $attr['type'];
$get_all_products = get_posts(array("post_type" => $products_type));

// echo "<pre>";
// print_r($get_all_products);



//Form Submit and check validation
if(isset($_GET['submit'])){

   if(isset($_GET['category'])){
    $category = $_GET['category'];
    // echo $category ."<br/>";
    }
if(isset($_GET['min'])){
    if(!empty($_GET['min'])){
    $minprice = $_GET['min'];
    // echo $minprice;
   }else{
    echo "Cannot submit empty min price"."<br/>";
   }
 }

if(isset($_GET['max'])){
    if(!empty($_GET['max'])){
    $maxprice = $_GET['max'];
    // echo $maxprice;
   }else{
    echo "Cannot submit empty min price"."<br/>";
    }
  }
if(isset($minprice) && isset($maxprice)){
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'hcf_price',
            'value' => array($minprice, $maxprice),
            'compare' => 'BETWEEN'
        ),
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_category',
            'field'    => 'slug',
            'terms'    => $category,
        ),
    ),
);
$query = new WP_Query($args);
// print_r($query); 
}

}
if(isset($query)){
if( $query->have_posts() ) :
while($query -> have_posts()) : $query -> the_post();
// echo "<pre>";
// print_r($query);
?>
<h1><?php the_title(); ?></h1>
<?php echo "<pre>";
// print_r(get_post_meta( get_the_ID(), 'hcf_price', true));?>
<p><strong>Price</strong> $ <?php print_r(get_post_meta( get_the_ID(), 'hcf_price', true )); ?></p>
<p><strong>Category</strong> : <?php the_terms(get_the_ID(), 'product_category', ' ', ', ', '');?></p>
<p><strong>SKU</strong> : <?php print_r(get_post_meta( get_the_ID(), 'hcf_sku', true )); ?></p>

<?php
endwhile;
wp_reset_query();
endif;

}else{
    
	foreach($get_all_products as $index=> $post){
    echo "<h2><a href='<?php the_permalink() ?>'>".$post->post_title."</a></h2>" ;
    echo "<h2>" .$post->post_title. "</h2>";
    echo "<p>".$post->post_content."</p>" ;
    echo "<p>".$post->post_excerpt."</p>" ;
    echo the_terms($post->ID, 'product_category', 'Category: ', ', ', '');
	    echo "<p><strong>Price</strong> $".get_post_meta( $post->ID, 'hcf_price', true )."</p>" ;
        echo "<p><strong>SKU</strong> $" . get_post_meta( $post->ID, 'hcf_sku', true )."</p>" ;
        
        echo "<p>------------------------------------------------------------------------</p>";
	}


  


}

}
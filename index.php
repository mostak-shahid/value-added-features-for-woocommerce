<?php 
/*
Plugin Name: Value Added Features for WooCommerce
Description: Base of future plugin
Version: 0.0.1
Author: Md. Mostak Shahid
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once( plugin_dir_path( __FILE__ ) . 'plugins/metabox/init.php');
require_once( plugin_dir_path( __FILE__ ) . 'metaboxes.php');
//Try 1
/*function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
	// Has our option been selected?
	if( ! empty( $_POST['extended_warranty'] ) ) {
		$product = wc_get_product( $product_id );
		$price = $product->get_price();
	// Store the overall price for the product, including the cost of the warranty
		$cart_item_data['warranty_base_price'] = 250;
		$cart_item_data['warranty_totla_price'] = $price + $cart_item_data['warranty_base_price'];
		$cart_item_data['warranty_price_title'] = "Warranty Price";
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 10, 3 );
function before_calculate_totals( $cart_obj ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	// Iterate through each cart item
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		if( isset( $value['warranty_totla_price'] ) ) {
			$price = $value['warranty_totla_price'];
			$value['data']->set_price( ( $price ) );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'before_calculate_totals', 10, 1 );

function add_excerpt_in_cart_item_name( $item_name,  $cart_item,  $cart_item_key ){
    $excerpt = wp_strip_all_tags( get_the_excerpt($cart_item['product_id']), true );
    $style = ' style="font-size:14px; line-height:normal;"';
    $excerpt_html = '<br>
        <p name="short-description"'.$style.'>'.$cart_item['warranty_price_title'].' '.$cart_item['warranty_base_price'].'</p>';

	//var_dump($cart_item);
    //die();
    return $item_name . $excerpt_html;
}
add_filter( 'woocommerce_cart_item_name', 'add_excerpt_in_cart_item_name', 10, 3 );*/

//Try 2
/*add_filter( 'woocommerce_add_cart_item' , 'set_woo_prices');
add_filter( 'woocommerce_get_cart_item_from_session',  'set_session_prices', 20 , 3 );

function set_woo_prices( $woo_data ) {
	if ( ! isset( $_GET['price'] ) || empty ( $_GET['price'] ) ) { return $woo_data; }
	$woo_data['data']->set_price( $_GET['price'] );
	$woo_data['my_price'] = $_GET['price'];
	return $woo_data;
}

function  set_session_prices ( $woo_data , $values , $key ) {
    if ( ! isset( $woo_data['my_price'] ) || empty ( $woo_data['my_price'] ) ) { return $woo_data; }
    $woo_data['data']->set_price( $woo_data['my_price'] );
    return $woo_data;
}*/

//Try 3
// Add the field to the product
add_action('woocommerce_before_add_to_cart_button', 'my_custom_checkout_field');
function my_custom_checkout_field() {
    global $product;
    $product_id = $product->get_id();

    // Get the field name of InputText1
    $option_name = get_post_meta($product_id, '_mos_vaffw_option_name', true);
    $option_app = get_post_meta($product_id, '_mos_vaffw_option_app', true);
    $details_group = get_post_meta($product_id, '_mos_vaffw_details_group', true);
    //_mos_vaffw_details_group[0][_mos_vaffw_feature_title]
    //_mos_vaffw_details_group[0][_mos_vaffw_feature_value]
    // $label = "Warranty Price";
	$start_u = "<label>";
	$end_u = "</label>";
    if( ! empty( $option_name ) ){
    	$start = $end = '';

    	if ($option_app == 'select') {
    		$start = '<select name="custom_slug">';
    		$end = '</select>';
    		$start_u = '<option';
    		$end_u = '</option>';
    	}

    	echo '<div id="InputText1">'.$option_name.'</div>';

    	if (sizeof($details_group)){
    		echo $start;
    		foreach ($details_group as $value) {
    			echo $start_u;
    			if ($option_app == 'select') echo ' value="'.$value["_mos_vaffw_feature_value"].'">';
    			else echo '<input type="'.$option_app.'" name="custom_slug" value="'.$value["_mos_vaffw_feature_value"].'">';
    			echo  $value["_mos_vaffw_feature_title"]; 
    			echo $end_u;				
    		}
    		echo $end;
    	}
        // echo '<div id="InputText1">
        //     <label><input type="checkbox" name="custom_slug" value="50">'.$option_name.':</label>
        // </div>';
    }
}

// Store custom field label and value in cart item data
add_filter( 'woocommerce_add_cart_item_data', 'save_my_custom_checkout_field', 10, 2 );
function save_my_custom_checkout_field( $cart_item_data, $product_id ) {
	$option_name = get_post_meta($product_id, '_mos_vaffw_option_name', true);
    if( isset( $_REQUEST['custom_slug'] ) ) {
        //$cart_item_data['custom_data']['label'] = get_post_meta($product_id, 'InputText1', true);
        $cart_item_data['custom_data']['label'] = $option_name;
        $cart_item_data['custom_data']['value'] = $_REQUEST['custom_slug'];
        $cart_item_data['custom_data']['ukey'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}

function add_value_calculate_totals( $cart_obj ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	// Iterate through each cart item


	
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		if( isset( $value['custom_data']['value'] ) ) {
			$c_price = $value['custom_data']['value'];
			$product = wc_get_product( $value['product_id'] );
			$price = $product->get_price();
			$f_price = $price + $c_price;
			$value['data']->set_price( ( $f_price ) );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'add_value_calculate_totals', 10, 1 );

// Display items custom fields label and value in cart and checkout pages
add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout', 10, 2 );
function render_meta_on_cart_and_checkout( $cart_data, $cart_item ){

    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['custom_data'] ) ) {
        $custom_items[] = array(
            'name' => $cart_item['custom_data']['label'],
            'value' => $cart_item['custom_data']['value'],
        );
    }
    return $custom_items;
}


// Save item custom fields label and value as order item meta data
add_action('woocommerce_add_order_item_meta','save_in_order_item_meta', 10, 3 );
function save_in_order_item_meta( $item_id, $values, $cart_item_key ) {
    if( isset( $values['custom_data'] ) ) {
        wc_add_order_item_meta( $item_id, $values['custom_data']['label'], $values['custom_data']['value'] );
    }
}
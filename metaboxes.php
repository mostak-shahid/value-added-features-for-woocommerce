<?php
function mos_testimonial_metaboxes() {
    $prefix = '_mos_vaffw_';   

    $mos_vaffw_details = new_cmb2_box(array(
        'id' => $prefix . 'details',
        'title' => __('Value Added Features for WooCommerce Product', 'cmb2'),
        'object_types' => array('product'),
    ));
    $mos_vaffw_details->add_field(array(
        'name' => 'Option Name',
        'id' => $prefix . 'option_name',
        'type' => 'text'
    ));

    $mos_vaffw_details->add_field( array(
        'name'             => 'Option apperance',
        'desc'             => 'Select an option',
        'id'               => $prefix . 'option_app',
        'type'             => 'select',
        'default'          => 'checkbox',
        'options'          => array(
            'checkbox' => __( 'Checkbox', 'cmb2' ),
            'select'   => __( 'Dropdown List', 'cmb2' ),
            'radio'     => __( 'Radio Button', 'cmb2' ),
        ),
    ) );
    $mos_vaffw_details_id = $mos_vaffw_details->add_field( array(
        'id'   => $prefix . 'details_group',
        'type' => 'group',
        'repeatable'  => true, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'       => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Entry', 'cmb2' ),
            'remove_button'     => __( 'Remove Entry', 'cmb2' ),
            'sortable'          => true,
            // 'closed'         => true, // true to have the groups closed by default
            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
        ),
    )); 

    $mos_vaffw_details->add_group_field( $mos_vaffw_details_id, array(
        'name' => 'Feature title',
        'id'   => $prefix . 'feature_title',
        'type' => 'text',
    )); 

    $mos_vaffw_details->add_group_field( $mos_vaffw_details_id, array(
        'name' => 'Feature value',
        'id'   => $prefix . 'feature_value',
        'type' => 'text',
    ));       

}
add_action('cmb2_admin_init', 'mos_testimonial_metaboxes');
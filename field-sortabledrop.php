<?php
/**
 * Plugin Name: Meta Box Sortable Drop Custom Field
 * Plugin URI: https://www.cftoolbox.io
 * Description: A Sortable and Droppable field that takes a list of custom values and allows you to select a portion and sort the order.
 * Version: 1.1
 * Author: Badabing Breda
 * Author URI: https://www.badabing.nl
 * License: MIT
 */

define( 'SORTABLEDROP_FIELD_DIR', plugin_dir_path( __FILE__ ) );
define( 'SORTABLEDROP_FIELD_URL', plugins_url( '/', __FILE__ ) );
define( 'SORTABLEDROP_FIELD_VERSION' , '1.0.0' );
// init on ... well .. init..
add_action( 'init' , 'badabing_sortabledrop_init' );

/**
 * callback that adds field multimask
 * @return [type] [description]
 */
function badabing_sortabledrop_init() {

    if ( class_exists( 'RWMB_Field' ) ) {

        /* only pass in 2 parameters, that's all we need */
        add_filter( 'rwmb_get_value' , 'sortabledrop_get_value' , 100, 2 );

        function sortabledrop_get_value( $value , $field ) {
            if ($field['type'] == 'sortabledrop' ) {
                // split the saved meta value
            	return ( $value !=='' ) ? explode( ',', $value ): false;

            }
            // if not returned earlier return as is
            return $value;
        }

        class RWMB_Sortabledrop_Field extends RWMB_Field {

        	/**
        	 * load this script when field is used
        	 * @return [type] [description]
        	 */
        	public static function admin_enqueue_scripts() {
        		wp_enqueue_script( 'mb-sortabledrop-field', SORTABLEDROP_FIELD_URL . "js/sortable.min.js", array() , SORTABLEDROP_FIELD_VERSION , false );

                wp_enqueue_style( 'mb-sortabledrop-css', SORTABLEDROP_FIELD_URL . 'css/bb-sortabledrop.css' );
        	}

            /**
             * Output html for this field
             * @param  [type] $meta  [description]
             * @param  [type] $field [description]
             * @return [type]        [description]
             */
            public static function html( $meta, $field ) {

                // init $return_string
                $return_string = '';
                $in = array();
                $out = array();

                $default_options = array(
                    'options'			=> array(),
                    'shared'			=> false,
                    'area_titles'		=> array( 'drop' => 'Selected' , 'options' => 'Options' ),
                    'sort_handle_html'	=> "<span class=\"sort-handle\">&nbsp;</span>",
                    'sort_handle'		=> '.sort-handle',
                );

                // parse the field settings
                $field = wp_parse_args(
                    $field,
                    $default_options
                );

                // split the saved meta value
            	$meta_order = ( $meta !=='' ) ? explode( ',', $meta ): array();

                // get the options keys, either index-# [0,1,2..] or index-value [ 'name', 'name2', 'name3' ]
                $options_keys = array_keys( $field['options'] );

                // get the difference in keys so we can list those in the second list
                $diff_options_keys = array_diff( $options_keys , $meta_order );

                // create flex container
                $return_string .= '<div id="__row_'. $field[ 'id' ]. '" class="bb-sortabledrop-row">';

                if ( $field['shared'] ) {

	                // loop over the options and determine which ones are in or out the ones matching with the meta
	                if ( count( $meta_order ) > 0 && $meta_order != '' ) {
						for ($idx = 0; $idx < count($meta_order); $idx++) {
                            if ( !isset( $field['options'][ $meta_order[ $idx ] ] ) ) continue;
							$in[] 	= 		sprintf("<li data-id='%s'><div>%s%s</div></li>",
													$meta_order[ $idx ],
													$field[ 'sort_handle_html' ],
													$field['options'][ $meta_order[ $idx ] ]
												);
						}

	                }

					foreach ($diff_options_keys as $item ) {
						$out[] 	= 		sprintf("<li data-id='%s'><div>%s%s</div></li>",
												$item,
												$field[ 'sort_handle_html' ],
												$field['options'][ $item ]
											);

					}

                } else {
	                // loop over the options and determine which ones are in or out the ones matching with the meta
	                if ( count( $meta_order ) > 0 && $meta_order != '' ) {
						for ($idx = 0; $idx < count($meta_order); $idx++) {
                            if ( !isset( $field['options'][ $meta_order[ $idx ] ] ) ) continue;
							$in[] 	= 		sprintf("<li data-id='%s'><div>%s%s</div></li>",
													$meta_order[ $idx ],
													$field[ 'sort_handle_html' ],
													$field['options'][ $meta_order[ $idx ] ]
												);
						}

	                }

					foreach ($diff_options_keys as $item ) {
						$in[] 	= 		sprintf("<li data-id='%s'><div>%s%s</div></li>",
												$item,
												$field[ 'sort_handle_html' ],
												$field['options'][ $item ]
											);

					}

                }

                // create two lists, one that we want to use, one to select from
                if ( $field['shared'] ) {
                	$return_string .= '<div class="bb-sortabledrop col">';
                	$return_string .= '<h4>' .$field['area_titles']['drop']. '</h4>';

	                // add a for-show field-value. This is the drag and drop part
	                $return_string .= '<ul id="__' . $field['id'].  '" name="__' . $field['field_name'] . '" data-type="sortabledrop">';
	                for ($i=0; $i < count( $in ) ; $i++ ) {
	                	$return_string .= $in[ $i ];
	                }
	                $return_string .= '</ul>';
	                $return_string .= '</div>';

                    // add a for-show field-value. This is the drag and drop part
                	$return_string .= '<div class="bb-sortabledrop col shared">';
                	$return_string .= '<h4>' .$field['area_titles']['options']. '</h4>';
                    $return_string .= '<ul id="__' . $field['id']. '_shared" name="__' . $field['field_name'] . '_shared" class="shared" data-type="sortabledrop">';
	                for ($i=0; $i < count( $out ) ; $i++ ) {
	                	$return_string .= $out[ $i ];
	                }
                    $return_string .= '</ul>';
	                $return_string .= '</div>';

                } else {

                	$return_string .= '<div class="bb-sortabledrop col">';
                	//$return_string .= '<h4>' .$field['area_titles']['drop']. '</h4>';
	                $return_string .= '<ul id="__' . $field['id'].  '" name="__' . $field['field_name'] . '" class="bb-sortabledrop col" data-type="sortabledrop">';

	                for ($i=0; $i < count( $in ) ; $i++ ) {
	                	$return_string .= $in[ $i ];
	                }

	                $return_string .= '</ul>';
	                $return_string .= '</div>';


                }

                $return_string .= '</div>';

                /**
                 * Add an input field that will hold the actual values
                 * Need this one to update
                 */
                $return_string .= '<input type="sortabledrop" name="' . $field['field_name'] . '" id="' . $field['id'] . '" style="display:none;" value="'. $meta .'">';

                /**
                 * JAVASCRIPT
                 */
                if ( $field['shared'] ) {

                    $script = <<<EOT
        <script type="text/javascript">
            (function($) {
                $(document).ready( function() {
                    var el = document.getElementById('__%s');
                    var el_shared = document.getElementById('__%s_shared');
                    var sortable_%s = Sortable.create(el, {
                        handle: "%s",
                        ghostClass: 'bb-sortable-ghost-class' ,
                        group: 'shared_%s',
                        animation: 150,
                        store: {
                            set: function( sortable ){
                                document.getElementById( '%s' ).value = sortable.toArray();
                                }
                        },
                        easing: "cubic-bezier(1, 0, 0, 1)"
                    });
                    var sortable_%s_shared = Sortable.create(el_shared, { handle: ".sort-handle", ghostClass: 'bb-sortable-ghost-class' , group: 'shared_%s', animation: 150 });

                });
            })(jQuery);
        </script>
EOT;
                    $return_string .= sprintf( $script , $field['id'], $field['id'], $field['id'], $field['sort_handle'], $field['id'], $field['id'], $field['id'] , $field['id'] );

                } else {

                    $script = <<<EOT
        <script type="text/javascript">
            (function($) {
                $(document).ready( function() {
                    var el = document.getElementById('__%s');
                    var sortable_%s = Sortable.create(el, {
                    	handle: "%s",
                    	ghostClass: 'bb-sortable-ghost-class',
                    	animation: 150,
                    	store: {
                            set: function( sortable ){
                                document.getElementById( '%s' ).value = sortable.toArray();
                            }
                         },
                         easing: "cubic-bezier(1, 0, 0, 1)"
                    });
                });
            })(jQuery);

        </script>
EOT;
                    $return_string .= sprintf( $script , $field['id'] , $field['id'] , $field['sort_handle'], $field['id'] );

                }


                return $return_string;

            }

        }

    }

}

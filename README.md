
Meta Box SortableDrop Field
---
The sortable drop field is a fieldtype that allows you to either simply order a list of options or drag an option from the options area and place it in the selected area in the desired order.

## Usage:
Download and activate the plugin.

after activation you will have the 'sortabledrop' field-type at your displosal.

Optional settings for the field are:

    setting					default-value

    'options'				array() 	// list of options
    'shared'				false		// set to true to be able to drag from secondary options list
    'area_titles'			array( 'drop' => 'Selected', 'options' => 'Options' )
    'sort_handle_html'		'<span class="sort-handle"'>&nbsp;</span>'
    'sort_handle'			'.sort-handle'

Example fields:

            /* creates a field with a single sortable area */
            array(
                'id'    => 'dragme',
                'options' => array( 'one' => 'One', 'two' => 'Two', 'three' => 'Three', 'four' => 'Four', 'five' => 'Five', 'six'=> 'Six' ),
                'name'  => 'Drag Me',
                'type'  => 'sortabledrop',
                'shared'    => false,
            ),
            /* creates a field with a dropzone and dragzone */
            array(
                'id'    => 'randomdrag',
                'options' => array( 'one' => 'One', 'two' => 'Two', 'three' => 'Three', 'four' => 'Four', 'five' => 'Five', 'six'=> 'Six' ),
                'name'  => 'Set them at their order',
                'type'  => 'sortabledrop',
                'shared'    => true,
            ),


You don't need to use a key => value pairing array, a simple array will also work but items will be stored as their index-#. Moving them around will also lose their connection to the 'label' so not recommended.

            array(
                'id'    => 'randomdrag',
                'options' => array( 'One', 'Two', 'Three', 'Four', 'Five', 'Six' ),
                'name'  => 'Set them at their order',
                'type'  => 'sortabledrop',
                'shared'    => true,
            ),

Field values are stored as a string, but when retrieved using rwmb_meta they will be split into an array.

### changelog

**v1.0** Inital version

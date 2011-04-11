This WordPress plugin offers a very simple numeric navigation for paged archives.

To call it just use `do_action( 'lopali' );`.

You can alter some arguments by adding an option array to the action.

Example:

	do_action( 
		'lopali'
	,	array ( 
			'here_title' => 'Here be dragons.'
		,	'range'      => 100 
		) 
	);


Also there is a filter named `'lopali_item'` to change every link before it is added to the list.

Example:

	add_filter( 'lopali_item', 'add_li_markup' );
	
	function add_li_markup( $item )
	{
		return "<li>$item</li>";
	}
	
My code is based on the [work of Sergej Müller](http://playground.ebiene.de/2554/wordpress-pagebar-pluginlos/).
<?php
/*
Plugin Name: Toscho’s Logical Page Links
Description: Creates page numbers for archives with a highlighted current page on <code>do_action( 'lopali' )</code>
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL v2
*/
! defined( 'ABSPATH' ) and exit;

add_action( 'lopali', array ( 'Toscho_Pager', 'print_page_links' ), 10, 1 );

/**
 * Creates a numbered list of pages.
 *
 * @author toscho http://toscho.de *
 */
class Toscho_Pager
{
	/**
	 * Handler for do_action( 'lopali' );
	 *
	 * @param  array $options See get_page_links(),
	 * @return void
	 */
	public static function print_page_links( $options = array () )
	{
		$default = array (
			'range'      => 40
		,	'before'     => '<p class="pager">'
		,	'after'      => '</p>'
		,	'here_title' => 'You are here.'
		);

		$settings = array_merge( $default, (array) $options );

		print self::get_page_links(
			$settings['range']
		,	$settings['here_title']
		,	$settings['before']
		,	$settings['after']
		);
	}

	/**
	 * The page links as a string
	 *
	 * @param  int $range Number pages to show
	 * @param  string $here_title Content of the title attribute
	 * @param  string $before Text in front of the links.
	 * @param  string $after  Text after the links
	 * @return string
	 */
	public static function get_page_links(
		$range     = 40
	,	$here_title = 'You are here.'
	,	$before    = '<p class="pager">'
	,	$after     = '</p>'
	)
	{
		$count = self::get_max_num_pages();
		$page  = self::get_current_page_number();
		$ceil  = ceil($range / 2);
		$out   = '';

		// No paging, no link
		if ( $count <= 1 )
		{
			return '';
		}

		// Limit
		if ( $count > $range )
		{
			if ( $page <= $range )
			{
				$min = 1;
				$max = $range + 1;
			}
			elseif ( $page >= ( $count - $ceil ) )
			{
				$min = $count - $range;
				$max = $count;
			}
			elseif ( $page >= $range && $page < ( $count - $ceil ) )
			{
				$min = $page - $ceil;
				$max = $page + $ceil;
			}
		}
		else
		{
			$min = 1;
			$max = $count;
		}

		// Links
		if ( ! empty ( $min ) && ! empty ( $max ) )
		{
			for ( $i = $min; $i <= $max; $i++ )
			{
				$item = ( $i == $page )
					// We don't link to the current page.
					? " <b title='$here_title'>$i</b> "
					: " <a class=number href='" . get_pagenum_link( $i ) . "'>$i</a> ";

				// If you want a list (<ul> or <ol>) add a filter here.
				$item = apply_filters( 'lopali_item', $item );
				$out .= $item;
			}
		}

		$result = $before . $out . $after;

		return $result;
	}

	/**
	 * Highest number of paged archives
	 */
	public static function get_max_num_pages()
	{
		return isset ( $GLOBALS['wp_query']->max_num_pages )
			? (int) $GLOBALS['wp_query']->max_num_pages : 0;
	}

	/**
	 * Current page number for archive pages (search, tags etc.)
	 *
	 * @return int
	 */
	public static function get_current_page_number()
	{
		$page = isset ( $GLOBALS['paged'] ) ? (int) $GLOBALS['paged'] : 1;
		// if $paged was 0
		0 == $page and $page = 1;
		return $page;
	}
}
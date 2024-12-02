<?php
/*
Plugin Name: UoE Media Hopper Embed
Description: Converts Media Hopper links into video embeds in the WordPress TinyMCE editor.
Author: DLAM Applications Development Team
Version: 2.0
*/

require_once 'OpenGraph.php';
require_once 'classes/class-ed-embed-init-cache.php';
require_once 'classes/class-ed-embed-video-cache.php';

function add_media_hopper_embed_handler() {
	wp_embed_register_handler('mediahopper', '#https://media.ed.ac.uk/media/*#i', 'media_hopper_embed_handler');
}

add_action('init', 'add_media_hopper_embed_handler');

function media_hopper_embed_handler($matches, $attr, $url, $rawattr) {
	try {
		$ed_embed_video_cache = new Ed_Embed_Video_Cache();

		$embed = $ed_embed_video_cache->get($url);

		if (!empty($embed) && strpos($embed, '<iframe') === false) {
			return $embed;
		}

		// get open graph tags for url
		$openGraph = OpenGraph::fetch($url);

		// get open graph video url if one exists on the page
		$video_url = $openGraph->getVideoUrl();

		if ('' !== $video_url) {
            $embed = "<video controls width='525' height='394'><source src='$video_url' type='video/mp4'>Your browser does not support the video tag.</video>";
		}

		$ed_embed_video_cache->save($url, $embed);

		return $embed;

	} catch (Exception $e) {
		return '';
	}
}

/**
 * We pretty much just hijack wp_trim_excerpt below
 * We need to do this to strip out media hopper links
 * because the excerpt will try and render the embed which is very slow if not
 *
 * @param string $text
 * @return mixed|void
 */
function custom_wp_trim_excerpt($text = '') {
	$raw_excerpt = $text;

	if ('' == $text) {
		$text = get_the_content('');

		$text = preg_replace('#https://media.ed.ac.uk/media/[^\s]+#i', '', $text);

		$text = strip_shortcodes($text);

		/** This filter is documented in wp-includes/post-template.php */
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);

		/**
		 * Filters the number of words in an excerpt.
		 *
		 * @since 2.7.0
		 *
		 * @param int $number The number of words. Default 55.
		 */
		$excerpt_length = apply_filters('excerpt_length', 55);
		/**
		 * Filters the string in the "more" link displayed after a trimmed excerpt.
		 *
		 * @since 2.9.0
		 *
		 * @param string $more_string The string shown within the more link.
		 */
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
		$text         = wp_trim_words($text, $excerpt_length, $excerpt_more);
	}
	/**
	 * Filters the trimmed excerpt string.
	 *
	 * @since 2.8.0
	 *
	 * @param string $text        The trimmed text.
	 * @param string $raw_excerpt The text prior to trimming.
	 */
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');

add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');

register_activation_hook(__FILE__, 'embed_do_setup');

/**
 * Create cache when plugin is activated
 *
 * @return void
 */
function embed_do_setup() {
	(new Ed_Embed_Init_Cache())->initialise_mediahopper_table();
}
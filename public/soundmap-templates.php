<?php
/**
 * Global functions & template tags
 *
 * @link       https://github.com/codiceovvio/soundmap
 * @since      0.3.1
 *
 * @package    Sound Map
 * @package    Soundmap/includes
 */


/**
 * [soundmap_page_wrapper_start description]
 * %s [description]
 *
 * @return [type] [description]
 */
function soundmap_page_wrapper_start() {

	return Soundmap_Template_Hooks::page_wrapper_start();

}

/**
 * [soundmap_page_wrapper_end description]
 * %s [description]
 *
 * @return [type] [description]
 */
function soundmap_page_wrapper_end() {

	return Soundmap_Template_Hooks::page_wrapper_end();

}

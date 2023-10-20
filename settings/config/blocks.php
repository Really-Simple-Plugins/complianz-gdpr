<?php
defined('ABSPATH') or die();
/**
 * This file contains the blocks for the dashboard
 * @since 2.0
 */
if (!function_exists('cmplz_blocks')) {
	function cmplz_blocks() {
		if ( ! cmplz_user_can_manage() ) {
			return [];
		}
		$blocks = [
			[
				'id'       => 'progress',
				'header' => [
					'type' => 'react',
					'data' => 'ProgressHeader'
				],
				'content'  => [ 'data' => 'ProgressBlock' ],
				'footer'   => [ 'data' => 'ProgressFooter' ],
				'class'    => ' cmplz-column-2',
			],
			[
				'id'       => 'documents',
				'header' => [
					'type' => 'react',
					'data' => 'DocumentsHeader'
				],
				'content'  => [ 'data' => 'DocumentsBlock' ],
				'footer'   => [ 'data' => 'DocumentsFooter' ],
				'class'    => 'border-to-border',
			],
			[
				'id'       => 'tools',
				'header' => [
					'type' => 'react',
					'data' => 'ToolsHeader'
				],
				'content'  => [ 'type' => 'react', 'data' => 'Tools' ],
				'footer'   => [ 'type' => 'react', 'data' => 'ToolsFooter' ],

				'class'    => 'border-to-border',
			],
			[
				'id'       => 'tips_tricks',
				'header' => [
					'type' => 'text',
					'data' => __( "Tips & Tricks", 'complianz-gdpr' ),
				],
				'content'  => ['data' => 'TipsTricks' ],
				'footer'   => [ 'data' => 'TipsTricksFooter' ],
				'class'    => ' cmplz-column-2',
			],
			[
				'id'       => 'other-plugins',
				'header' => [
					'type' => 'react',
					'data' => 'OtherPluginsHeader'
				],
				'title'    => __( "Other Plugins", 'complianz-gdpr' ),
				'help'     => __( 'A help text', 'complianz-gdpr' ),
				'content'  => [ 'type' => 'react', 'data' => 'OtherPlugins' ],
				'footer'   => [ 'type' => 'html', 'data' => '' ],
				'class'    => ' cmplz-column-2 no-border no-background',
			],
		];

		return apply_filters( 'cmplz_blocks', $blocks );
	}
}

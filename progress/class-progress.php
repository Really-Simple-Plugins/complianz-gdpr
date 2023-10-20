<?php
defined('ABSPATH') or die();
class cmplz_progress {
	private static $_this;

	function __construct() {
		if ( isset( self::$_this ) )
			wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.', get_class( $this ) ) );
		self::$_this = $this;

		add_filter("cmplz_do_action", array($this, 'progress_actions'), 10, 3);
	}

	static function this() {
		return self::$_this;
	}

	public function progress_actions($data, $action, $request) {
		if ( $action === 'dismiss_task' ) {
			$data = $this->dismiss_task( $request['id'] );
		}
		if ( $action === 'get_notices' ) {
			$data = [
				'notices' => $this->notices(),
				'show_cookiebanner' => cmplz_cookiebanner_should_load(true),
			];
		}

		return $data;
	}

	public function notices(): array {
		$notices = COMPLIANZ::$admin->get_warnings(array( 'status' => 'all' ));
		$out = [];
		foreach ($notices as $id => $notice ) {
			$notice['id'] = $id;
			$out[] =  $notice;
		}
		return $out;
	}

	/**
	 * Calculate the percentage completed in the dashboard progress section
	 * Determine max score by adding $notice['score'] to the $max_score variable
	 * Determine actual score by adding $notice['score'] of each item with a 'success' output to $actual_score
	 * @return int
	 *
	 * @since 4.0
	 *
	 */

	private function percentage(): int {
		if ( ! cmplz_user_can_manage() ) {
			return 0;
		}

		$max_score    = 0;
		$actual_score = 0;
		$notices = COMPLIANZ::$admin->get_warnings(array(
			'status' => 'all',
		));
		foreach ( $notices as $id => $notice ) {
			if (isset( $notice['score'] )) {
				// Only items matching condition will show in the dashboard. Only use these to determine max count.
				$max_score += (int) $notice['score'];
				$success   = isset( $notice['icon'] ) && ( $notice['status'] === 'success' );
				if ( $success ) {
					// If the output is success, task is completed. Add to actual count.
					$actual_score += (int) $notice['score'];
				}
			}
		}
		$score = $max_score>0 ? $actual_score / $max_score :0;
		return (int) round( $score * 100 );
	}


	/**
	 * Count number of premium notices we have in the list.
	 * @return int
	 */
	public function get_lowest_possible_task_count(): int {
		$premium_notices = COMPLIANZ::$admin->get_warnings(array('premium_only'=>true));
		return count($premium_notices) ;
	}

	public function dismiss_from_admin_notice(){
		if ( !cmplz_user_can_manage() ) {
			return;
		}

		if (isset($_GET['dismiss_notice'])) {
			$id = sanitize_title($_GET['dismiss_notice']);
			$this->dismiss_task($id);
		}
	}

	/**
	 * Process the react dismissal of a task
	 *
	 * Since 3.1
	 *
	 * @access public
	 *
	 */

	public function dismiss_task($id): array {
		if ( !empty($id) ) {
			$id = sanitize_title( $id );
			$dismissed_warnings = get_option( 'cmplz_dismissed_warnings', array() );
			if ( ! in_array( $id, $dismissed_warnings, true ) ) {
				$dismissed_warnings[] = $id;
				update_option( 'cmplz_dismissed_warnings', $dismissed_warnings, false );
			}
			$count = get_transient( 'cmplz_plusone_count' );
			if (is_numeric($count) && $count>0) {
				$count--;
			}
			set_transient('cmplz_plusone_count', $count, WEEK_IN_SECONDS);
			//remove this notice from the admin notices list
			$notices = get_transient( 'cmplz_admin_notices' );
			if (isset($notices[$id])) {
				unset($notices[$id]);
			}
			set_transient('cmplz_admin_notices', $notices, DAY_IN_SECONDS);
		}

		return [];
	}


}

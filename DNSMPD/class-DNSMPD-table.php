<?php
/**
 * DNSMPD Reports Table Class
 *
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class cmplz_DNSMPD_Table extends WP_List_Table {

	/**
	 * Number of items per page
	 *
	 * @var int
	 * @since 1.5
	 */
	public $per_page = 30;

	/**
	 * Number of results found
	 *
	 * @var int
	 * @since 1.7
	 */
	public $count = 0;

	/**
	 * Total results
	 *
	 * @var int
	 * @since 1.95
	 */
	public $total = 0;

	/**
	 * The arguments for the data set
	 *
	 * @var array
	 * @since  2.6
	 */
	public $args = array();

	/**
	 * Get things started
	 *
	 * @since 1.5
	 * @see   WP_List_Table::__construct()
	 */
	public function __construct() {
		global $status, $page;

		// Set parent defaults
		parent::__construct( array(
			'singular' => __( 'User', 'complianz-gdpr' ),
			'plural'   => __( 'Users', 'complianz-gdpr' ),
			'ajax'     => false,
		) );

	}

	/**
	 * Show the search field
	 *
	 * @param string $text     Label for the search box
	 * @param string $input_id ID of the search box
	 *
	 * @return void
	 * @since 1.7
	 *
	 */
	public function search_box( $text, $input_id ) {
		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="'
			     . esc_attr( $_REQUEST['orderby'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="'
			     . esc_attr( $_REQUEST['order'] ) . '" />';
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text"
			       for="<?php echo esc_attr($input_id) ?>"><?php echo esc_html($text); ?>:</label>
			<input type="search" id="<?php echo esc_attr($input_id) ?>" name="s"
			       value="<?php _admin_search_query(); ?>"/>
			<?php submit_button( $text, 'button', false, false,
				array( 'ID' => 'search-submit' ) ); ?>
		</p>
		<?php
	}

	/**
	 * Gets the name of the primary column.
	 *
	 * @return string Name of the primary column.
	 * @since  2.5
	 * @access protected
	 *
	 */
	protected function get_primary_column_name() {
		return 'name';
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param array  $item        Contains all the data of the customers
	 * @param string $column_name The name of the column
	 *
	 * @return string Column Name
	 * @since 1.5
	 *
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'resolved' :
				$value = $item['resolved'] ? __('Resolved','complianz-gdpr') : __('Open','complianz-gdpr');
				break;
			default:
				$value = isset( $item[ $column_name ] ) ? $item[ $column_name ] : null;
				break;
		}
		return apply_filters( 'cmplz_dnsmpd_column_' . $column_name, $value, $item['ID'] );
	}

	/**
	 * Column name
	 * @param array $item
	 *
	 * @return string
	 */

	public function column_name( $item ) {
		$name    = '#' . $item['ID'] . ' ';
		$name    .= ! empty( $item['name'] ) ? $item['name'] : '<em>' . __( 'Unnamed user', 'complianz-gdpr' ) . '</em>';
		$actions = array(
			'resolve' => '<a href="' . admin_url( 'admin.php?page=cmplz-datarequests&action=resolve&id=' . $item['ID'] ) . '">' . __( 'Resolve', 'complianz-gdpr' ) . '</a>',
			'delete' => '<a href="' . admin_url( 'admin.php?page=cmplz-datarequests&action=delete&id=' . $item['ID'] ) . '">' . __( 'Delete', 'complianz-gdpr' ) . '</a>',
		);

		return $name . $this->row_actions( $actions );
	}

	/**
	 * Retrieve the table columns
	 *
	 * @return array $columns Array of all the list table columns
	 * @since 1.5
	 */
	public function get_columns() {
		$columns = array(
			'name'  => __( 'Name', 'complianz-gdpr' ),
			'email' => __( 'Email', 'complianz-gdpr' ),
			'region' => __( 'Region', 'complianz-gdpr' ),
			'resolved' => __( 'Status', 'complianz-gdpr' ),
			'datarequest' => __( 'Data request', 'complianz-gdpr' ),
			'date' => __( 'Date', 'complianz-gdpr' ),
		);

		return apply_filters( 'cmplz_report_customer_columns', $columns );

	}

	/**
	 * Get the sortable columns
	 *
	 * @return array Array of all the sortable columns
	 * @since 2.1
	 */
	public function get_sortable_columns() {
		return array(
				'request_date' => array( 'request_date', true ),
				'name'         => array( 'name', true ),
				'region'       => array( 'region', true ),
				'email'        => array( 'email', true ),
				'resolved'     => array( 'resolved', true ),
				'date'         => array( 'date', true ),
				'datarequest'  => array( 'datarequest', true ),
		);
	}

	/**
	 * Outputs the reporting views
	 *
	 * @return void
	 * @since 1.5
	 */
	public function bulk_actions( $which = '' ) {
		// These aren't really bulk actions but this outputs the markup in the right place
	}

	/**
	 * Retrieve the current page number
	 *
	 * @return int Current page number
	 * @since 1.5
	 */
	public function get_paged() {
		return isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
	}

	/**
	 * Retrieves the search query string
	 *
	 * @return mixed string If search is present, false otherwise
	 * @since 1.7
	 */
	public function get_search() {
		return ! empty( $_GET['s'] ) ? urldecode( trim( $_GET['s'] ) ) : false;
	}


	public function resolved_select() {
		// Month Select
		$options = [
				1 => __('Resolved',"complianz-gdpr"),
				0 => __('Unresolved',"complianz-gdpr"),
		];
		$selected = 0;
		if ( isset($_GET['cmplz_resolved_select'])		) {
			$selected = intval($_GET['cmplz_resolved_select']);
		}

		echo '<select style="float:right" name="cmplz_resolved_select" id="cmplz_resolved_select" class="cmplz_resolved_select">';
		foreach($options as $value => $label) {
			echo '<option value="' . $value . '" ' . ($selected==$value ? 'selected' : '') . '>' . $label . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Build all the reports data
	 *
	 * @return array $reports_data All the data for customer reports
	 * @global object $wpdb Used to query the database using the WordPress
	 *                      Database API
	 * @since 1.5
	 */

	public function reports_data() {

		$data    = array();
		$paged   = $this->get_paged();
		$offset  = $this->per_page * ( $paged - 1 );
		$search  = $this->get_search();
		$order   = isset( $_GET['order'] )
			? sanitize_text_field( $_GET['order'] ) : 'DESC';
		$orderby = isset( $_GET['orderby'] )
			? sanitize_text_field( $_GET['orderby'] ) : 'id';

		$args = array(
			'number'  => $this->per_page,
			'offset'  => $offset,
			'order'   => $order,
			'orderby' => $orderby
		);

		if ( is_email( $search ) ) {
			$args['email'] = $search;
		} else {
			$args['name'] = $search;
		}

		if ( isset( $_GET['cmplz_resolved_select'] ) ) {
			$args['resolved'] = intval($_GET['cmplz_resolved_select']);
		}

		$this->args = $args;
		$requests  = COMPLIANZ::$DNSMPD->get_requests( $args );
		if ( $requests ) {
			foreach ( $requests as $request ) {
				$datarequest='';
				$options = apply_filters( 'cmplz_datarequest_options', [] );
				foreach ($options as $fieldname => $label ) {
					if ( $request->{$fieldname}==1 ) {
						$datarequest = '<a href="https://complianz.io/'.$label['slug'].'" target="_blank">'.$label['short'].'</a>';
					}
				}
				$time = date( get_option( 'time_format' ), $request->request_date );
				$date = cmplz_localize_date(date(get_option('date_format'),$request->request_date));
				$date = cmplz_sprintf( __( "%s at %s", 'complianz-gdpr' ), $date, $time );
//				$region = isset(COMPLIANZ::$config->regions[$request->region]['label_full']) ? COMPLIANZ::$config->regions[$request->region]['label_full'] :;
				$region = cmplz_region_icon( $request->region, 25);
				$data[] = array(
						'ID'          => $request->ID,
						'name'        => $request->name,
						'email'       => $request->email,
						'region'      => $region,
						'resolved'    => $request->resolved,
						'datarequest' => $datarequest,
						'date'       => $date,
				);
			}
		}

		return $data;
	}

	/**
	 * Prepare items for the table
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array(); // No hidden columns
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $this->reports_data();
		$this->total = COMPLIANZ::$DNSMPD->count_requests( $this->args );
		$total_pages = $this->per_page ? ceil( (int) $this->total / (int) $this->per_page ) : 1;
		$this->set_pagination_args( array(
			'total_items' => $this->total,
			'per_page'    => $this->per_page,
			'total_pages' => $total_pages,
		) );
	}
}

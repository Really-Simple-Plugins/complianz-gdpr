<?php defined( 'ABSPATH' ) or die( "you do not have access to this page!" );?>

<?php $progress = COMPLIANZ::$wizard->wizard_percentage_complete(); ?>
<?php $premium = ( ! defined( 'cmplz_premium' ) ) ? true : false; ?>
<?php $orange = $progress < 80 ? 'orange' : ''; ?>

	<style>
		@keyframes cmplz-load-progress-bar {
			0% { width: 0; }
			100% { width: <?php echo $progress?>%; }
		}
	</style>
	<div class="cmplz-progress-bar">
		<div class="cmplz-progress-bar-value <?php echo $orange ?>"></div>
	</div>
	<div class="cmplz-grid-progress">
		<div class="cmplz-progress-percentage">
			<?php echo $progress?>%
		</div>
		<div class="cmplz-progress-description">
			<?php if ( $progress < 100
			) {
				$warnings = COMPLIANZ::$admin->get_warnings( array(
						'status' => array('urgent', 'open'),
				) );
				$warning_count = count( $warnings );
				echo __( 'Consent Management is activated on your site.',  'complianz-gdpr' );
				if ($warning_count > 0) {
					echo ' ' . sprintf( _n( "You still have %s task open.", "You still have %s tasks open.", $warning_count, 'complianz-gdpr' ), $warning_count );
				}


			} else {
				if ( $premium ) {
					_e( 'Well done! Your website is ready for your selected regions.', 'complianz-gdpr' );
				} else {
					$regions = cmplz_get_regions();
					if ( count($regions)>0 ) {
						$laws = [];
						foreach ($regions as $region => $value) {
							$laws[] = COMPLIANZ::$config->regions[$region]['law'];
						}
						printf( __( 'Great! Your website is configured for %s.', 'complianz-gdpr' ),  implode(', ', $laws) );
					}
				}
			} ?>
		</div>
	</div>

	<div class="cmplz-scroll-container">
			<?php
			$warning_args = array(
				'cache' => false,
			);
			if ( isset($_GET['status']) && $_GET['status'] === 'remaining' ) {
				$warning_args['status'] = array('urgent', 'open');
			}
			$warnings = COMPLIANZ::$admin->get_warnings($warning_args);
			if (count($warnings) == 0 ){
				//make sure we don't have an empty space
				$warnings['no-tasks'] = array(
						'status' => 'completed',
						'plus_one' => false,
						'message'    => cmplz_sprintf(
								__( 'You have no new tasks! Have a look at our %sdocumentation%s and see all the possibilities Complianz has to offer.', 'complianz-gdpr' ),
						'<a href="https://complianz.io/docs/" target="_blank">',
								'</a>'
						),
				);
				?>
			<?php }
			$status_message = '';
			foreach ( $warnings as $id => $warning) {
				$status = $warning['status'];
				$plus_one = $warning['plus_one'];
				if ( $status === 'completed' ) {
					$status_message = __("Completed", 'complianz-gdpr');
				}
				if ( $status === 'open' ) {
					$status_message = __("Open", 'complianz-gdpr');
				}
				if ( $status === 'urgent' ) {
					$status_message = __("Urgent", 'complianz-gdpr');
				}
				if ( $status === 'premium' ) {
					$status_message = __("Premium", 'complianz-gdpr');
				}

				?>
				<div class="cmplz-progress-warning-container">
					<div class="cmplz-progress-status-container">
						<span class="cmplz-progress-status cmplz-<?php echo $status?>"><?php echo $status_message?></span>
					</div>
					<div>
						<?php echo $warning['message'] ?>
						<?php if ( $plus_one ) { ?>
							<span class="cmplz-plusone">1</span>
						<?php } ?>
					</div>
					<?php if ( $warning['dismissible'] ) { ?>
					<button type="button" class="cmplz-dismiss-warning" data-warning_id="<?php echo $id?>">
						<span class="cmplz-close-warning-x">
							<?php echo cmplz_icon('times', 'default', false, 13 )?>
						</span>
					</button>
					<?php } ?>
				</div>
				<?php
			}
			?>

	</div>
<?php


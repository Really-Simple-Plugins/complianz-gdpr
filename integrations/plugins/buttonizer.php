<?php
function cmplz_buttonizer_consent() {
	?>
	<script>
		document.addEventListener('cmplz_before_cookiebanner', function() {
			if (cmplz_has_consent('statistics')) {
				enableButtonizer();
			}
		});
		document.addEventListener('cmplz_status_change', function (e) {
			if (e.detail.category === 'statistics' && e.detail.value==='allow') {
				enableButtonizer();
			}
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'cmplz_buttonizer_consent' );

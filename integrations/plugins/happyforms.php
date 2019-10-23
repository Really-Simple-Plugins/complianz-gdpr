<?php
defined('ABSPATH') or die("you do not have acces to this page!");

function cmplz_happyforms_initform(){
    ?>
    <script>
        jQuery(document).ready(function($){
            $(document).on("cmplzRunAfterAllScripts", cmplzRunHappyFormsScript);
            function cmplzRunHappyFormsScript() {
                if ($('.happyforms-form').length) $('.happyforms-form').happyForm();
            }
        })
    </script>
<?php }

add_action('wp_footer', 'cmplz_happyforms_initform');
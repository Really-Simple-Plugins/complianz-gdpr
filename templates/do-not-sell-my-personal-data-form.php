<?php
//firstname is honeypot
?>
<div class="cmplz-dnsmpd alert">
    <span class="close">&times;</span>
    <span id="message"></span>
</div>

<div id="cmplz-dnsmpd-form">
    <input type="search" class="dnsmpd-firstname" value="" placeholder="your first name" id="cmplz_dnsmpd_firstname">
    <input type="text" required value="" placeholder="<?php echo __('Your name','complianz-gdpr')?>" id="cmplz_dnsmpd_name">
    <input type="email" required value="" placeholder="<?php echo __('email@email.com','complianz-gdpr')?>" id="cmplz_dnsmpd_email">
    <input type="button" id="cmplz-dnsmpd-submit"  value="<?php echo __('Send','complianz-gdpr')?>">
</div>



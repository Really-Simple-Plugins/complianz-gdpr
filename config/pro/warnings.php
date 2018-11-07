<?php
defined('ABSPATH') or die("you do not have acces to this page!");
$this->warning_types = $this->warning_types + array(
    'needs-consent-boxes' => array(
        'region' => 'eu',
        'type' => 'general',
//        'label_ok' => __('Great! You have contact forms which require a consent checkbox, and have implemented this', 'complianz'),
        'label_error' => __('You should add a consent checkbox to your contact forms.', 'complianz')
    ),
    'suggested-policy-text-changed' => array(
        'type' => 'general',
        'label_ok' => __('No changes in plugin privacy policies have been detected.', 'complianz'),
            'label_error' => __('Changes in plugin privacy policies have been detected.', 'complianz') . " " . sprintf(__('Please review step %s of the wizard.', 'complianz'), $this->steps_to_review_on_changes),
        ),
    'missing-processing-agreements' => array(
        'type' => 'document',
        'label_ok' => __('Processing agreement with all processors and/or Service Providers.', 'complianz'),
        'label_error' => sprintf(__('You have processors and/or Service Providers without a processing agreement. %sCreate a processing agreement%s.', 'complianz'), '<a href="'.admin_url('admin.php?page=cmplz-wizard&step='.$this->get_step_by_id('company').'&section='.$this->get_section_by_id('sharing_of_data_eu')).'">', '</a>'),
    ),
    'privacy-policy-12months-not-updated' => array(
        'region' => 'us',
        'type' => 'general',
        'label_ok' => __('The privacy statement was updated within the past 12 months.', 'complianz'),
        'label_error' => __('Your privacy statement was last updated more than 12 months ago. You should review the wizard.', 'complianz'),
    ),
);
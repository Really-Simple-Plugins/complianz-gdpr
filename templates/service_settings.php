<div class="cmplz-service-field cmplz-field" data-service_id="{service_id}">
    <div class="{disabledClass}">
        <div><label><?php _e('Name', 'complianz-gdpr')?></label></div>

        <input type="text" {disabled}
               class="cmplz_name" name="cmplz_name"
               value="{name}">
    </div>
    <div class="{disabledClass}">
        <div><label><?php _e('Service type', 'complianz-gdpr')?></label></div>
        <select class="cmplz-select2-no-additions cmplz_serviceType" type="text" {disabled} name="cmplz_serviceType">
            {serviceTypes}
        </select>
    </div>
    <div class="{disabledClass}">
        <label>
            <input type="checkbox" {disabled}
                   name="cmplz_sharesData"
                   class="cmplz_sharesData"
                   {sharesData}">
            <?php _e('Data is shared with this service', 'complianz-gdpr') ?>
        </label>
    </div>
    <div class="{disabledClass}">
        <div><label><?php _e('Privacy Statement URL', 'complianz-gdpr')?></label></div>

        <input type="text" {disabled}
               class="cmplz_privacyStatementURL" name="cmplz_privacyStatementURL"
               value="{privacyStatementURL}">
    </div>


    <div class="{syncDisabledClass}">
        <label>
            <input {syncDisabled} type="checkbox"
                   name="cmplz_sync"
                   class="cmplz_sync"
                   {sync}">
            <?php _e('Sync service info with cookiedatabase.org', 'complianz-gdpr') ?>
        </label>
    </div>
    <div>
        {link}
    </div>

    <button class="button cmplz-edit-item" type="button" data-action="save" data-type="service" name="cmplz-save-item" ><?php _e('Save','complianz-gdpr')?></button>

    <button class="button cmplz-edit-item" type="button" data-action="delete" data-type="service"
            name="cmplz_remove_item"><?php _e("Delete", 'complianz-gdpr') ?></button>
</div>
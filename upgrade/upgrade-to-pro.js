/**
 * Check if the installation folder already exists
 */
function rsp_install_pro_destination_clear() {

    rsp_progress_bar_start();

    // Get arguments from url
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    var data = {
        'action': 'rsp_upgrade_destination_clear',
        'token'  : rsp_upgrade.token,
        'plugin'  : urlParams.get('plugin'),
        'install_pro' : true,
    };

    ajax.get(rsp_upgrade.admin_url, data, function(response) {

        var step_destination_clear = document.getElementsByClassName("step-destination-clear");
        var step_color = step_destination_clear[0].getElementsByClassName("step-color");
        var step_text = step_destination_clear[0].getElementsByClassName("step-text");

        var data = JSON.parse(response);

        if ( data && data.success != undefined ) {

            if ( data.success ) {
                step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Destination clear</span>";
                rsp_progress_bar_finish();

                rsp_install_pro_activate_licence();
            } else {
                step_color[0].innerHTML = "<div class='rsp-red rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Destination folder already exists</span>";
                rsp_progress_bar_stop();

                document.getElementsByClassName("rsp-btn rsp-cancel")[0].classList.remove("rsp-hidden");
            }

        }

    });

}


/**
 * Link the license with this sites url and the site where you download the plugin
 */
function rsp_install_pro_activate_licence() {

    rsp_progress_bar_start();

    // Get arguments from url
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    var data = {
        'action': 'rsp_upgrade_activate_license',
        'token'  : rsp_upgrade.token,
        'license'  : urlParams.get('license'),
        'item_id'  : urlParams.get('item_id'),
        'api_url'  : urlParams.get('api_url'),
        'install_pro' : true,
    };

    ajax.get(rsp_upgrade.admin_url, data, function(response) {

        var step_activate_license = document.getElementsByClassName("step-activate-license");
        var step_color = step_activate_license[0].getElementsByClassName("step-color");
        var step_text = step_activate_license[0].getElementsByClassName("step-text");

        var data = JSON.parse(response);

        if ( data && data.status != undefined ) {

            if ( data.status == "valid" ) {
                step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>License activated</span>";
                rsp_progress_bar_finish();

                rsp_install_pro_get_package_information();
            }

            if ( data.status == "invalid" ) {
                step_color[0].innerHTML = "<div class='rsp-red rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>License invalid</span>";

                rsp_progress_bar_stop();

                document.getElementsByClassName("rsp-btn rsp-cancel")[0].classList.remove("rsp-hidden");
            }

            if ( data.status == "error" ) {
                step_color[0].innerHTML = "<div class='rsp-red rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>" + data.message + "</span>";

                rsp_progress_bar_stop();

                document.getElementsByClassName("rsp-btn rsp-cancel")[0].classList.remove("rsp-hidden");
            }
        }

    });

}


function rsp_install_pro_get_package_information() {

    rsp_progress_bar_start();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    var data = {
        'action': 'rsp_upgrade_package_information',
        'token'  : rsp_upgrade.token,
        'license'  : urlParams.get('license'),
        'item_id'  : urlParams.get('item_id'),
        'api_url'  : urlParams.get('api_url'),
        'install_pro' : true,
    };

    ajax.get(rsp_upgrade.admin_url, data, function(response) {

        var step_package_information = document.getElementsByClassName("step-package-information");
        var step_color = step_package_information[0].getElementsByClassName("step-color");
        var step_text = step_package_information[0].getElementsByClassName("step-text");

        var data = JSON.parse(response);

        if ( data && data.success != undefined ) {

            if ( data.success ) {
                step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Package information gathered</span>";
                rsp_progress_bar_finish();

                rsp_install_pro_install_plugin( data.download_link );
            } else {
                step_color[0].innerHTML = "<div class='rsp-red rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Failed to gather package information</span>";
                rsp_progress_bar_stop();

                document.getElementsByClassName("rsp-btn rsp-cancel")[0].classList.remove("rsp-hidden");
            }

        }

    });

}

function rsp_install_pro_install_plugin( download_link ) {

    rsp_progress_bar_start();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    var data = {
        'action': 'rsp_upgrade_install_plugin',
        'token'  : rsp_upgrade.token,
        'download_link'  : download_link,
        'license'  : urlParams.get('license'),
        'item_id'  : urlParams.get('item_id'),
        'api_url'  : urlParams.get('api_url'),
        'install_pro' : true,
    };

    ajax.get(rsp_upgrade.admin_url, data, function(response) {

        var step_install_plugin = document.getElementsByClassName("step-install-plugin");
        var step_color = step_install_plugin[0].getElementsByClassName("step-color");
        var step_text = step_install_plugin[0].getElementsByClassName("step-text");

        var data = JSON.parse(response);

        if ( data && data.success != undefined ) {

            if ( data.success ) {
                step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Plugin installed</span>";
                rsp_progress_bar_finish();

                rsp_install_pro_activate_plugin();
            } else {
                step_color[0].innerHTML = "<div class='rsp-red rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Failed to install plugin</span>";
                rsp_progress_bar_stop();

                document.getElementsByClassName("rsp-btn rsp-cancel")[0].classList.remove("rsp-hidden");
            }

        }

    });

}


function rsp_install_pro_activate_plugin() {

    rsp_progress_bar_start();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    var data = {
        'action': 'rsp_upgrade_activate_plugin',
        'token'  : rsp_upgrade.token,
        'plugin'  : urlParams.get('plugin'),
        'install_pro' : true,
    };

    ajax.get(rsp_upgrade.admin_url, data, function(response) {
        var step_activate_plugin = document.getElementsByClassName("step-activate-plugin");
        var step_color = step_activate_plugin[0].getElementsByClassName("step-color");
        var step_text = step_activate_plugin[0].getElementsByClassName("step-text");

        var data = JSON.parse(response);

        if ( data && data.success != undefined ) {

            if ( data.success ) {
                step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Plugin activated</span>";
                rsp_progress_bar_finish();

                rsp_install_pro_activate_license_plugin();
            } else {
                step_color[0].innerHTML = "<div class='rsp-red rsp-bullet'></div>";
                step_text[0].innerHTML = "<span>Failed to activate plugin</span>";
                rsp_progress_bar_stop();

                document.getElementsByClassName("rsp-btn rsp-cancel")[0].classList.remove("rsp-hidden");
            }

        }
    });

}


function rsp_install_pro_activate_license_plugin() {

    rsp_progress_bar_start();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    if ( urlParams.get('plugin') == 'cmplz_pro' ) {
        var data = {
            'cmplz_nonce'  : rsp_upgrade.cmplz_nonce,
            'cmplz_license_activate'  : true,
            'cmplz_license_save'  : true,
            'cmplz_license_key'       : urlParams.get('license'),
            'install_pro' : true,
        };
    }

    if ( urlParams.get('license') == 'rsssl_pro' ) {
        var data = {
            'action' : 'rsp_upgrade_activate_plugin',
            'rsssl_pro_nonce'  : rsp_upgrade.rsssl_pro_nonce,
            'rsssl_pro_license_activate'  : true,
            'rsssl_pro_license_key'       : urlParams.get('license'),
            'install_pro' : true,
        };
    }

    ajax.post(rsp_upgrade.admin_url, data, function(response) {
        var step_activate_license_plugin = document.getElementsByClassName("step-activate-license-plugin");
        var step_color = step_activate_license_plugin[0].getElementsByClassName("step-color");
        var step_text = step_activate_license_plugin[0].getElementsByClassName("step-text");

        step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
        step_text[0].innerHTML = "<span>Plugin license activated</span>";
        rsp_progress_bar_finish();

        if ( urlParams.get('plugin') == 'cmplz_pro' ) {
            rsp_install_pro_deactivate_plugin();
        } else {
            document.getElementsByClassName("rsp-btn rsp-visit-dashboard")[0].classList.remove("rsp-hidden");
        }
    });

}


/**
 * Deactivate the free plugin, only for complianz
 */
function rsp_install_pro_deactivate_plugin() {

    rsp_progress_bar_start();

    var data = {
        'action' : 'rsp_upgrade_deactivate_plugin',
        'token'  : rsp_upgrade.token,
        'install_pro' : true,
    };

    ajax.get(rsp_upgrade.admin_url, data, function(response) {
        var step_deactivate_plugin = document.getElementsByClassName("step-deactivate-plugin");
        var step_color = step_deactivate_plugin[0].getElementsByClassName("step-color");
        var step_text = step_deactivate_plugin[0].getElementsByClassName("step-text");

        step_color[0].innerHTML = "<div class='rsp-green rsp-bullet'></div>";
        step_text[0].innerHTML = "<span>Free plugin deactivated</span>";
        rsp_progress_bar_finish();

        document.getElementsByClassName("rsp-btn rsp-visit-dashboard")[0].classList.remove("rsp-hidden");
    });

}



/**
 * Progress bar
 */
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

let rsp_progress_bar = {
    current_step: 1,
    total_steps: 6,
    progress_procentage: 0,
    speed: 1,
};

if ( urlParams.get('plugin') == 'cmplz_pro' ) {
    rsp_progress_bar = {
        current_step: 1,
        total_steps: 7,
        progress_procentage: 0,
        speed: 1,
    };
}

function rsp_progress_bar_start() {
    rsp_progress_bar['speed'] = 0.25;
    rsp_progress_bar_move();
}

function rsp_progress_bar_finish() {
    rsp_progress_bar['speed'] = 1;
}

function rsp_progress_bar_stop() {
    rsp_progress_bar['speed'] = 0;
}

function rsp_progress_bar_move() {
    var to = rsp_progress_bar.current_step * 100 / rsp_progress_bar.total_steps;
    rsp_progress_bar['progress_procentage'] = Math.min(rsp_progress_bar.progress_procentage + rsp_progress_bar.speed, to);
    var progress_bar_container = document.getElementsByClassName("progress-bar-container");
    var progress = progress_bar_container[0].getElementsByClassName("progress");
    var bar = progress[0].getElementsByClassName("bar");
    bar[0].style = "width: " + rsp_progress_bar.progress_procentage + "%;";

    if ( rsp_progress_bar.speed != 0 && rsp_progress_bar.progress_procentage < to ) {
        setTimeout(rsp_progress_bar_move, 25 / rsp_progress_bar.speed);
    } else {
        if ( rsp_progress_bar.speed == 0 ) {
            bar[0].style = "width: 100%;";
            bar[0].classList.remove('rsp-green');
            bar[0].classList.add('rsp-red');
        } else {
            rsp_progress_bar.current_step++;
        }
    }
}


rsp_install_pro_destination_clear();



<?php
// Show the FACEBOOK login button
// the default title
if (api_is_anonymous()) {
    $button_url = api_get_path(WEB_PLUGIN_ASSET_PATH)."add_facebook_login_button/img/cnx_fb.png";
    if (!empty($plugin_info['settings']['add_facebook_login_button_facebook_button_url'])) {
        $button_url = api_htmlentities(
            $plugin_info['settings']['add_facebook_login_button_facebook_button_url']
        );
    }
    $_template['facebook_button_url'] = $button_url;
}


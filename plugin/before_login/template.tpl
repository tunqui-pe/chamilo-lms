{% if before_login.plugin_info.settings.before_login_option1 %}
    <div class="span12">
        <div class="row">
            <div class="span6">
                {{ before_login.plugin_info.settings.before_login_option1 }}
            </div>
            <div class="span6">
                {{ before_login.plugin_info.settings.before_login_option2 }}
            </div>
        </div>
    </div>
{% endif %}
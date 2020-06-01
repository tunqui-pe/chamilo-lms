<div class="row">
    <div class="col-md-12">
        {% if check %}
            <h2>{{ 'CongratulationsSence'|get_plugin_lang('SencePlugin') }}</h2>
        {% else %}
            <h2>{{ 'YouHaveSuccessLoggedOutSence'|get_plugin_lang('SencePlugin') }}</h2>
        {% endif %}
    </div>
</div>
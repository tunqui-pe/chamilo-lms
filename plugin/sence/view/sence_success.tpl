<div class="row">
    <div class="col-md-12">
        <div class="plugin_logo">
            <img alt="" class="img-responsive" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
        </div>
        {% if check %}
            <h2 class="text-center">{{ 'CongratulationsSence'|get_plugin_lang('SencePlugin') }}</h2>
            <div class="text-center">
                <a href="{{ url_course }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-external-link-square" aria-hidden="true"></i>
                    {{ 'StartCourse'|get_plugin_lang('SencePlugin') }}
                </a>
            </div>
        {% else %}
            <h2 class="text-center">{{ 'YouHaveSuccessLoggedOutSence'|get_plugin_lang('SencePlugin') }}</h2>
            <div class="text-center">
                <a href="{{ url_list_courses }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-external-link-square" aria-hidden="true"></i>
                    {{ 'ListCourse'|get_plugin_lang('SencePlugin') }}
                </a>
            </div>
        {% endif %}

    </div>
</div>
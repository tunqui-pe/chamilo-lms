<div class="row">
    <div class="col-md-12">
        <div class="plugin_logo">
            <img alt="" class="img-responsive" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
        </div>
        <div class="text-center">
            <h2>{{ 'FailedSence'|get_plugin_lang('SencePlugin') }}</h2>
            <div class="error_code">
                {{ 'Error'|get_plugin_lang('SencePlugin') }} - {{ error_code }}
            </div>
            <div class="alert alert-danger" role="alert">
                {{ message_error }}
            </div>
            <a href="{{ url_list_courses }}" class="btn btn-primary btn-lg">
                <i class="fa fa-external-link-square" aria-hidden="true"></i>
                {{ 'ListCourse'|get_plugin_lang('SencePlugin') }}
            </a>
        </div>
    </div>
</div>
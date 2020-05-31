
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="plugin_logo">
                    <img alt="" class="img-responsive" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
                </div>
                {% if sence %}
                    <div class="alert alert-info" role="alert">
                        {{ 'ThisCourseHasSenceCode' | get_lang }}
                    </div>
                {% else %}
                    <div class="alert alert-warning" role="alert">
                        {{ 'NotHaveAnAssociatedSenceCourseCode' | get_lang }}
                    </div>
                {% endif %}

                <div class="tools text-center">
                    {% if sence %}
                        <a href="{{ url_edit_sence }}" class="btn btn-success">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                            {{ 'EditCodeSence'|get_plugin_lang('SencePlugin') }}
                        </a>
                        <a href="{{ url_delete_sence }}" class="btn btn-danger">
                            <i class="fa fa-codepen" aria-hidden="true"></i>
                            {{ 'DeleteCodeSence'|get_plugin_lang('SencePlugin') }}
                        </a>
                    {% else %}
                        <a href="{{ url_add_sence }}" class="btn btn-primary">
                            <i class="fa fa-codepen" aria-hidden="true"></i>
                            {{ 'AddCodeSence'|get_plugin_lang('SencePlugin') }}
                        </a>

                    {% endif %}
                </div>


            </div>
        </div>
    </div>
</div>


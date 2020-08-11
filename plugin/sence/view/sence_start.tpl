<div class="row">
    <div class="col-md-12">

        <div class="plugin_logo">
            <img alt="" class="img-responsive" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
        </div>

        <div class="tools text-center">
            {% if sence %}
                {% if is_teacher %}
                    <a href="{{ url_list }}" class="btn btn-primary">
                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                        {{ 'HistoryList'|get_plugin_lang('SencePlugin') }}
                    </a>
                {% endif %}
                {% if is_admin %}
                    <a href="{{ url_edit_sence }}" class="btn btn-success">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        {{ 'EditCodeSence'|get_plugin_lang('SencePlugin') }}
                    </a>

                    <a href="{{ url_delete_sence }}" class="btn btn-danger">
                        <i class="fa fa-codepen" aria-hidden="true"></i>
                        {{ 'DeleteCodeSence'|get_plugin_lang('SencePlugin') }}
                    </a>
                {% endif %}
            {% else %}
                {% if is_admin %}
                    <a href="{{ url_add_sence }}" class="btn btn-primary">
                        <i class="fa fa-codepen" aria-hidden="true"></i>
                        {{ 'AddCodeSence'|get_plugin_lang('SencePlugin') }}
                    </a>
                {% endif %}
            {% endif %}
        </div>
        {% if is_admin or is_teacher %}
            {% if not sence %}
                <div class="alert alert-warning" role="alert">
                    {{ 'NotHaveAnAssociatedSenceCourseCode' | get_plugin_lang('SencePlugin') }}
                </div>
            {% else %}
                <div class="alert alert-info" role="alert">
                    {{ 'ThisCourseHasSenceCode' | get_plugin_lang('SencePlugin') }}
                </div>

                <div class="alert alert-success" role="alert">
                    {{ 'SynchronizationEnabledCourseSence' | get_plugin_lang('SencePlugin') }}
                </div>
            {% endif %}
            {% if is_admin %}
                <ul>
                    <li>{{ 'ConfigurePluginSence' | get_plugin_lang('SencePlugin') }}</li>
                    <li>{{ 'ObtainingToken' | get_plugin_lang('SencePlugin') }}</li>
                </ul>
            {% endif %}

        {% endif %}


        {% if is_student %}

            {% if not sence %}
                <div class="alert alert-warning" role="alert">
                    {{ 'NotHaveAnAssociatedSenceCourseCode' | get_plugin_lang('SencePlugin') }}
                </div>
            {% else %}
                <div class="alert alert-success" role="alert">
                    {{ 'SynchronizationEnabledCourseSence' | get_plugin_lang('SencePlugin') }}
                </div>
            {% endif %}

            {% if sence %}
                <div class="sence">
                    <div class="sence-login">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-8">
                                <div class="card bg-secondary border-0 mb-0">
                                    <div class="card-header">
                                        {% if check %}
                                            {{ 'InfoUserSence'|get_plugin_lang('SencePlugin') }}
                                        {% else %}
                                            {{ 'UserLoginSence'|get_plugin_lang('SencePlugin') }}
                                        {% endif %}
                                    </div>
                                    <div class="card-body">
                                        <dl class="dl-horizontal">
                                            <dt>{{ 'RutOtecCompany'|get_plugin_lang('SencePlugin') }}</dt>
                                            <dd>{{ rut_otec }}</dd>
                                            <dt>{{ 'NameOtecCompany'|get_plugin_lang('SencePlugin') }}</dt>
                                            <dd>{{ company_name }}</dd>
                                            <dt>{{ 'CodeSence'|get_plugin_lang('SencePlugin') }}</dt>
                                            <dd>{{ sence.code_sence }}</dd>
                                            {% if sence.action_id != 1 %}
                                                <dt>{{ 'CodeCourse'|get_plugin_lang('SencePlugin') }}</dt>
                                                <dd>{{ sence.code_course }}</dd>
                                            {% endif %}
                                            {% if check %}
                                                <dt>{{ 'CodeCourse'|get_plugin_lang('SencePlugin') }}</dt>
                                                <dd>{{ sence.code_course }}</dd>
                                            {% endif %}
                                            <dt>{{ 'NameCourseSence'|get_plugin_lang('SencePlugin') }}</dt>
                                            <dd>{{ course.title }}</dd>
                                            {% if check %}
                                                <dt>{{ 'RunStudentSence'|get_plugin_lang('SencePlugin') }}</dt>
                                                <dd>{{ sence_user.run_student }}</dd>
                                                <dt>{{ 'DateLoginSence'|get_plugin_lang('SencePlugin') }}</dt>
                                                <dd>{{ sence_user.date_login }}</dd>
                                                <dt>{{ 'TimeZoneSence'|get_plugin_lang('SencePlugin') }}</dt>
                                                <dd>{{ sence_user.time_zone }}</dd>
                                            {% endif %}
                                        </dl>

                                        {{ form_login }}

                                    </div>
                                </div>
                                {% if scholar %}
                                    <div class="alert alert-info" role="alert">
                                        {{ 'ScholarSence'|get_plugin_lang('SencePlugin') }}
                                    </div>
                                {% else %}
                                    <ul>
                                        <li>{{ 'RegisterCS'|get_plugin_lang('SencePlugin') }}</li>
                                        <li>{{ 'RecoverCS'|get_plugin_lang('SencePlugin') }}</li>
                                        <li>{{ 'ChangeCS'|get_plugin_lang('SencePlugin') }}</li>
                                        <li>{{ 'UpdateData'|get_plugin_lang('SencePlugin') }}</li>
                                    </ul>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
            {% endif %}
        {% endif %}

    </div>
</div>

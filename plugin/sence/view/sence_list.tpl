<div class="row">
    <div class="col-md-12">
        <h3 class="page-header">{{ 'HistoryListLogs'|get_plugin_lang('SencePlugin') }}</h3>
        <table class="table table-hover tablet-logs">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ 'Firstname'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'Lastname'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'CodeCourse'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'MultiAction'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'RunStudentSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'SessionSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'DateLoginSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TimeZoneSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TrainingLineSmall'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TypeLogin'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TypeError'|get_plugin_lang('SencePlugin') }}</th>
            </tr>
            </thead>
            <tbody>
            {% for user in lists %}
                {% set status = 'row-ok' %}
                {% if user.glosa_error != 0 %}
                    {% set status = 'row-error' %}
                {% endif %}
            <tr class="{{ status }}">
                <th scope="row">
                    {{ user.id }}
                </th>
                <td>{{ user.firstname }}</td>
                <td>{{ user.lastname }}</td>
                <td>{{ user.code_course }}</td>
                <td>{{ user.action_id }}</td>
                <td>{{ user.run_student  }}</td>
                <td>
                    {% if user.id_session_sence %}
                        {{ user.id_session_sence  }}
                    {% else %}
                        {{ 'NotRegister'|get_plugin_lang('SencePlugin') }}
                    {% endif %}
                </td>
                <td>{{ user.date_login  }}</td>
                <td>{{ user.time_zone  }}</td>
                <td>
                    {{ user.training_line }}
                </td>
                <td>
                    {% if user.type_login == 1 %}
                        {{ 'OpenLogin'|get_plugin_lang('SencePlugin') }}
                    {% elseif user.type_login == 3 %}
                        {{ 'ErrorLogin'|get_plugin_lang('SencePlugin') }}
                    {% else %}
                        {{ 'CloseLogin'|get_plugin_lang('SencePlugin') }}
                    {% endif %}
                </td>
                <td>
                    {{ user.glosa_error }}
                </td>
            </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>
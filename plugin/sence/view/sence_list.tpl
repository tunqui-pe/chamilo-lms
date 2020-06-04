<div class="row">
    <div class="col-md-12">
        <h3 class="page-header">{{ 'HistoryListLogs'|get_plugin_lang('SencePlugin') }}</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ 'Name'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'CodeCourse'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'RunStudentSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'SessionSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'DateLoginSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TimeZoneSence'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TrainingLine'|get_plugin_lang('SencePlugin') }}</th>
                <th>{{ 'TypeLogin'|get_plugin_lang('SencePlugin') }}</th>
            </tr>
            </thead>
            <tbody>
            {% for user in lists %}
            <tr>
                <th scope="row">
                    {{ user.id }}
                </th>
                <td>{{ user.people_name }}</td>
                <td>{{ user.code_course }}</td>
                <td>{{ user.run_student  }}</td>
                <td>{{ user.id_session_sence  }}</td>
                <td>{{ user.date_login  }}</td>
                <td>{{ user.time_zone  }}</td>
                <td>
                    {{ user.training_line }}
                </td>
                <td>
                    {% if user.type_login == 1 %}
                        {{ 'OpenLogin'|get_plugin_lang('SencePlugin') }}
                    {% else %}
                        {{ 'CloseLogin'|get_plugin_lang('SencePlugin') }}
                    {% endif %}
                </td>
            </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>
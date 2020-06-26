<div class="sence-msg">
    <p>{{ 'SenceContentEmail'|get_plugin_lang('SencePlugin') }}</p>
    <table border="0" width="100%">
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold; width: 30%;">
                {{ 'Firstname'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.firstname }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'Lastname'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.lastname }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'CodeCourse'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.code_course }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'RunStudentSence'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.run_student }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'SessionSence'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.code_sence }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'DateLoginSence'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.date_login }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'TimeZoneSence'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.time_zone }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'TrainingLineSmall'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.training_line }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'TypeLogin'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {% if user.type_login == 1 %}
                    {{ 'OpenLogin'|get_plugin_lang('SencePlugin') }}
                {% elseif user.type_login == 3 %}
                    {{ 'ErrorLogin'|get_plugin_lang('SencePlugin') }}
                {% else %}
                    {{ 'CloseLogin'|get_plugin_lang('SencePlugin') }}
                {% endif %}
            </td>
        </tr>
        <tr>
            <td style="background-color: #e8e8e8; padding: 5px 15px; font-weight: bold;">
                {{ 'TypeError'|get_plugin_lang('SencePlugin') }}
            </td>
            <td style="padding: 5px 15px;">
                {{ user.glosa_error }} - {{ error_msg }}
            </td>
        </tr>
    </table>
</div>
<div class="sence-logo" style="text-align: center; padding: 1rem;">
    <img alt="" width="140px" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
</div>
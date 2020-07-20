<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                {% if form_room %}
                    {% if is_admin %}
                        <div class="alert alert-info" role="alert">
                            {{ 'MessageMeetingAdmin'|get_plugin_lang('ZoomEasyPlugin') }}
                        </div>
                    {% endif %}
                    {% if is_teacher %}
                        <div class="alert alert-success" role="alert">
                            {{ 'MessageMeetingTeacher'|get_plugin_lang('ZoomEasyPlugin') }}
                        </div>
                    {% endif %}

                    {{ form_room }}

                {% else %}

                    <h3 class="page-header">{{ 'ListRoomsAccounts'|get_plugin_lang('ZoomEasyPlugin') }}</h3>
                    {% if zooms %}
                        {% set number = 0 %}
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ 'MeetingIDZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}</th>
                                <th>{{ 'RoomNameZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}</th>
                                <th>{{ 'InstantMeetingURL'|get_plugin_lang('ZoomEasyPlugin') }}</th>
                                <th>{{ 'TypeRoom'|get_plugin_lang('ZoomEasyPlugin') }}</th>
                                {% if not view_pass %}
                                    <th>{{ 'AccountEmailZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}</th>
                                    <th>{{ 'Password'|get_lang }}</th>

                                {% endif %}
                                <th>{{ 'Status'|get_lang }}</th>
                                <th>{{ 'Actions'|get_lang }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for zoomeasy in zooms %}
                                {% set number = number + 1 %}
                                <tr>
                                    <th scope="row">{{ number }}</th>
                                    <td>{{ zoomeasy.room_id }}</td>
                                    <td>{{ zoomeasy.room_name }}</td>
                                    <td>
                                        <a href="{{ zoomeasy.room_url }}">
                                            {{ zoomeasy.room_url }}
                                        </a>
                                    </td>
                                    <td>
                                        {% if zoomeasy.type_room ==1 %}
                                            {{ 'GeneralRoom'|get_plugin_lang('ZoomEasyPlugin') }}
                                        {% else %}
                                            {{ 'PersonalRoom'|get_plugin_lang('ZoomEasyPlugin') }}
                                        {% endif %}
                                    </td>
                                    {% if not view_pass %}
                                        <td>{{ zoomeasy.zoom_email }}</td>
                                        <td>{{ zoomeasy.zoom_pass }}</td>
                                    {% endif %}
                                    <td>{{ zoomeasy.activate }}</td>
                                    <td>
                                        {{ zoomeasy.actions }}
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% endif %}

                {% endif %}

            </div>
        </div>
    </div>
</div>

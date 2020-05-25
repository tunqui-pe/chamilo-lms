<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                {% if form_room %}
                    {% if is_admin %}
                        <div class="alert alert-info" role="alert">
                            {{ 'MessageMeetingAdmin'|get_plugin_lang('ZoomPlugin') }}
                        </div>
                    {% endif %}
                    {% if is_teacher %}
                        <div class="alert alert-success" role="alert">
                            {{ 'MessageMeetingTeacher'|get_plugin_lang('ZoomPlugin') }}
                        </div>
                    {% endif %}

                    {{ form_room }}

                {% else %}

                    <h3 class="page-header">{{ 'ListRoomsAccounts'|get_plugin_lang('ZoomPlugin') }}</h3>
                    {% if zooms %}
                        {% set number = 0 %}
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ 'MeetingIDZoom'|get_plugin_lang('ZoomPlugin') }}</th>
                                <th>{{ 'RoomNameZoom'|get_plugin_lang('ZoomPlugin') }}</th>
                                <th>{{ 'InstantMeetingURL'|get_plugin_lang('ZoomPlugin') }}</th>
                                <th>{{ 'TypeRoom'|get_plugin_lang('ZoomPlugin') }}</th>
                                <th>{{ 'AccountEmailZoom'|get_plugin_lang('ZoomPlugin') }}</th>
                                {% if not view_pass %}
                                    <th>{{ 'Password'|get_lang }}</th>
                                    <th>{{ 'Status'|get_lang }}</th>
                                {% endif %}
                                <th>{{ 'Actions'|get_lang }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for zoom in zooms %}
                                {% set number = number + 1 %}
                                <tr>
                                    <th scope="row">{{ number }}</th>
                                    <td>{{ zoom.room_id }}</td>
                                    <td>{{ zoom.room_name }}</td>
                                    <td>
                                        <a href="{{ zoom.room_url }}">
                                            {{ zoom.room_url }}
                                        </a>
                                    </td>
                                    <td>
                                        {% if zoom.type_room ==1 %}
                                            {{ 'GeneralRoom'|get_plugin_lang('ZoomPlugin') }}
                                        {% else %}
                                            {{ 'PersonalRoom'|get_plugin_lang('ZoomPlugin') }}
                                        {% endif %}
                                    </td>
                                    {% if not view_pass %}
                                        <td>{{ zoom.zoom_email }}</td>
                                        <td>{{ zoom.zoom_pass }}</td>
                                    {% endif %}
                                    <td>{{ zoom.activate }}</td>
                                    <td>
                                        {{ zoom.actions }}
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
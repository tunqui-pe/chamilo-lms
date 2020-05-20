<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                {% if form_room %}

                    {{ form_room }}

                {% else %}

                    <h3 class="page-header">{{ 'ListRoomsAccounts'|get_lang }}</h3>
                    {% if zooms %}
                        {% set number = 0 %}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ 'MeetingIDZoom'|get_lang }}</th>
                            <th>{{ 'RoomNameZoom'|get_lang }}</th>
                            <th>{{ 'InstantMeetingURL'|get_lang }}</th>
                            <th>{{ 'AccountEmailZoom'|get_lang }}</th>
                            <th>{{ 'Password'|get_lang }}</th>
                            <th>{{ 'Status'|get_lang }}</th>
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
                            <td>{{ zoom.zoom_email }}</td>
                            <td>{{ zoom.zoom_pass }}</td>
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
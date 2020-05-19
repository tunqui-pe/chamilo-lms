
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="plugin_logo">
                    <img alt="" class="img-responsive" src="{{ _p.web }}plugin/zoom/resources/img/chamilo_zoom.svg">
                </div>

                    <div class="tools text-center">
                        {% if is_admin %}
                            <a href="{{ url_list_room }}" class="btn btn-primary">
                                Gestionar cuentas Zoom Meeting
                            </a>
                        {% endif %}
                        {% if is_admin or is_teacher %}
                            {% if room %}
                                <a href="{{ url_change_room }}" class="btn btn-danger">
                                    Quitar sala Zoom asociada
                                </a>
                            {% else %}
                                <a href="{{ url_add_room }}" class="btn btn-success">
                                    Asociar una sala Zoom
                                </a>
                            {% endif %}
                        {% endif %}
                    </div>


                {% if room %}
                <div class="info-room">
                    <div class="alert alert-info" role="alert">
                        Este curso tiene asociada una sala Zoom Meeting
                    </div>
                    <div class="well">
                        <dl class="dl-horizontal">
                            <dt>{{ 'MeetingIDZoom'|get_lang }}</dt>
                            <dd>{{ room.room_id }}</dd>
                            <dt>{{ 'RoomNameZoom'|get_lang }}</dt>
                            <dd>{{ room.room_name }}</dd>
                            <dt>{{ 'InstantMeetingURL'|get_lang }}</dt>
                            <dd>
                                <a href="{{ room.room_url }}" target="_blank">
                                    {{ room.room_url }}
                                </a>
                            </dd>
                            <dt>{{ 'HostKey'|get_lang }}</dt>
                            <dd>{{ room.room_pass }}</dd>

                            {% if is_teacher %}
                            <dt>{{ 'AccountEmailZoom'|get_lang }}</dt>
                            <dd>{{ room.zoom_email }}</dd>
                            <dt>{{ 'Password'|get_lang }}</dt>
                            <dd>{{ room.zoom_pass }}</dd>
                            {% endif %}
                        </dl>
                        <div class="text-center">
                            <a href="{{ room.room_url }}" target="_blank" class="btn btn-success btn-lg">
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                Ingresar a la videoconferencia
                            </a>
                        </div>
                    </div>
                </div>
                {% endif %}

                {{ form_zoom }}
            </div>
        </div>
    </div>
</div>


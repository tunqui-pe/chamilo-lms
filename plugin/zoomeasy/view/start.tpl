<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="plugin_logo">
                    <img alt="" class="img-responsive" src="{{ _p.web }}plugin/zoomeasy/resources/img/chamilo_zoomeasy.svg">
                </div>

                <div class="tools text-center">
                    {% if is_admin or is_teacher %}
                        <a href="{{ url_list_room }}" class="btn btn-primary">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                            {{ 'ManageZoomEasyAccounts'|get_plugin_lang('ZoomEasyPlugin') }}
                        </a>
                    {% endif %}
                    {% if is_admin or is_teacher %}
                        {% if room %}
                            <a href="{{ url_change_room }}" class="btn btn-danger">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                {{ 'RemoveAndChangeRoomZoomEasy'| get_plugin_lang('ZoomEasyPlugin') }}
                            </a>
                        {% else %}
                            {% if is_add == false %}
                                <a href="{{ url_add_room }}" class="btn btn-success">
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    {{ 'AssociateZoomEasyAccount'| get_plugin_lang('ZoomEasyPlugin') }}
                                </a>
                            {% endif %}
                        {% endif %}
                    {% endif %}
                </div>


                {% if room %}
                    <div class="alert alert-info" role="alert">
                        {{ 'CourseCurrentlyAssociatedAccountZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}
                    </div>
                    <div class="info-room">
                        <div class="well">
                            <dl class="dl-horizontal">
                                <dt>{{ 'MeetingIDZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}</dt>
                                <dd>{{ room.room_id }}</dd>
                                <dt>{{ 'RoomNameZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}</dt>
                                <dd>{{ room.room_name }}</dd>
                                <dt>{{ 'InstantMeetingURL'|get_plugin_lang('ZoomEasyPlugin') }}</dt>
                                <dd>
                                    <a href="{{ room.room_url }}" target="_blank">
                                        {{ room.room_url }}
                                    </a>
                                </dd>
                                {% if room.room_pass %}
                                    <dt>{{ 'HostKey'|get_plugin_lang('ZoomEasyPlugin') }}</dt>
                                    <dd>{{ room.room_pass }}</dd>
                                {% endif %}

                                {% if is_teacher %}
                                    {% if not view_pass %}
                                        <dt>{{ 'AccountEmailZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}</dt>
                                        <dd>{{ room.zoom_email }}</dd>
                                        <dt>{{ 'Password'|get_lang }}</dt>
                                        <dd>{{ room.zoom_pass }}</dd>
                                    {% endif %}
                                {% endif %}
                            </dl>
                            <div class="text-center">
                                <a href="{{ room.room_url }}" target="_blank" class="btn btn-success btn-lg">
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    {{ 'JoinTheMeetingZoomEasy' | get_plugin_lang('ZoomEasyPlugin') }}
                                </a>
                            </div>
                        </div>
                    </div>
                {% else %}
                    <div class="alert alert-warning" role="alert">
                        {{ 'CourseDoesNotHaveAssociatedAccountZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}
                    </div>
                    {% if is_student %}
                        <div class="alert alert-info" role="alert">
                            {{ 'MessageMeetingZoomEasy'|get_plugin_lang('ZoomEasyPlugin') }}
                        </div>
                    {% endif %}
                {% endif %}

                {{ form_zoomeasy }}

            </div>
        </div>
    </div>
</div>


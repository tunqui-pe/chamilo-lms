{% extends template ~ "/layout/layout_1_col.tpl" %}

{% block content %}
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-3">
            <div class="sm-groups">
                {{ social_avatar_block }}
                {{ social_menu_block }}
            </div>
        </div>
        <div class="col-md-9">
            {{ create_link }}
            {% if is_group_member == false %}
                <div class="social-group-details-info">
                    {{ 'Privacy' | trans }}

                    {% if group_info.visibility == 1 %}
                        {{ 'ThisIsAnOpenGroup' | trans }}
                    {% else %}
                        {{ 'ThisIsACloseGroup' | trans }}
                    {% endif %}
                </div>
            {% endif %}

            {{ social_right_content }}

            <div id="display_response_id" class="col-md-5"></div>
            {{ social_auto_extend_link }}
        </div>

    </div>
{% endblock %}

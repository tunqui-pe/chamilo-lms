<div class="row issued">
    <div class="col-md-5">
        <div class="thumbnail">
            <figure class="text-center">
                <img class="img-responsive center-block" src="{{ skill_info.badge_image }}" alt="{{ skill_info.name }}">
                <figcaption>
                    <p class="lead">{{ skill_info.name }}</p>
                    {% if skill_info.short_code %}
                        <p>{{ skill_info.short_code }}</p>
                    {% endif %}
                </figcaption>
            </figure>
            <div class="caption">
                {% if skill_info.description %}
                    <p>{{ skill_info.description }}</p>
                {% endif %}
                {% if skill_info.criteria %}
                    <h3>{{ 'CriteriaToEarnTheBadge'|get_lang }}</h3>
                    <p>{{ skill_info.criteria }}</p>
                {% endif %}
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <h3>{{ 'RecipientDetails'|get_lang }}</h3>
        <p class="lead">{{ user_info.complete_name }}</p>
        <h4>{{ 'SkillAcquiredAt'|get_lang }}</h4>
        <ul class="fa-ul">
        {% for course in skill_info.courses %}
            <li>
                {% if course.name %}
                    <em class="fa-li fa fa-clock-o fa-fw"></em> {{ 'TimeXThroughCourseY'|get_lang|format(course.date_issued, course.name) }}
                {% else %}
                    <em class="fa-li fa fa-clock-o fa-fw"></em> {{ course.date_issued }}
                {% endif %}
                {% if course.argumentation %}
                    <p>{{ course.argumentation }}</p>
                {% endif %}
            </li>
        {% endfor %}
        </ul>
        <hr>
        {% if allow_export %}
            <p class="text-center">
                <a href="#" class="btn btn-success" id="badge-export-button">
                    <em class="fa fa-external-link-square fa-fw"></em> {{ 'ExportBadge'|get_lang }}
                </a>
            </p>
        {% endif %}
    </div>
</div>
{% if allow_export %}
    <script>
        $(document).on('ready', function () {
            $('#badge-export-button').on('click', function (e) {
                e.preventDefault();

                OpenBadges.issue({{ assertions|json_encode() }});
            });
        });
    </script>
{% endif %}

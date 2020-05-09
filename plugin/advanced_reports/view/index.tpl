{% if _u.is_admin %}
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills">
                <li role="presentation">
                    <a onclick="$('#report').val(1);$('.general-field').css('display', 'none');$('.rep1').css('display', 'block');">Reporte Alumnos Inscritos por Sesion</a>
                </li>
                <li role="presentation">
                    <a onclick="$('#report').val(2);$('.general-field').css('display', 'none');$('.rep2').css('display', 'block');">Reporte de Conexión de Alumnos y Tutores en Sesión</a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <form method="GET">
                <br/>
                <input type="text" class="rep1 general-field" placeholder="AñoMes" name="period" />
                <br/>
                Session:
                <select class="rep2 general-field" name="session_id">
                    {% for session in sessionList %}
                    <option value="{{ session.id }}">
                        {{ session.name }}
                    </option>
                    {% endfor %}
                </select>
                <br/>
                Course:
                <select  class="rep2 general-field" name="course_code">
                    {% for course in courseList %}
                    <option value="{{ course.code }}">
                        {{ course.title }}
                    </option>
                    {% endfor %}
                </select>

                <input type="hidden" id="report" name="report"/>
                <button>Consultar</button>
                <button value="1" name="excel">Excel</button>
            </form>

            <table class="table table-bordered table-hover">
            {% for row in data %}
                <tr>
                    {% for rowText in row %}
                        <th>{{ rowText }}</th>
                    {% endfor %}
                </tr>
            {% endfor %}
            </table>
        </div>
    </div>
{% endif %}

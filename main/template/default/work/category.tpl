{{ form }}

{% if categories %}
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre de categoria</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
                {% for category in categories %}
                <tr>
                    <th scope="row">{{ category.id }}</th>
                    <td>{{ category.name }}</td>
                    <td>
                        {{ category.actions }}
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
{% endif %}
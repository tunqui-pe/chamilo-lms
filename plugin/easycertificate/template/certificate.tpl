<!DOCTYPE html>
<head>
    {{ css_certificate }}
</head>
<body style="margin: 0; padding: 0;">
    {% if background_h %}
        <div id="page-a" style="background-image: url('{{ background_h }}'); background-size: cover; width: 1200px; height: 793px; position: relative;">
    {% else %}
        <div id="page-a" style="width: 1200px; height: 793px; position: relative;">
    {% endif %}
        <div style="padding: {{ margin }};">
            {{ front_content }}
        </div>
        </div>
    {% if(later_content) %}
        <div id="page-b" class="caraB" style="page-break-before:always; margin:0; padding:2rem;">
            {{ later_content }}
        </div>
    {% endif %}
</body>
</html>
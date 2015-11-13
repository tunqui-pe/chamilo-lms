<p>{{ 'Dear'|trans }} {{ complete_name }},</p>
<p>{{ 'YouAreRegisterToSessionX'|trans|format(session_name) }}</p>
<p>{{ 'Address'|trans }}  {{ _s.site_name }} {{ 'Is'|trans }}
    : {{ _p.web }}</p>
<p>{{ 'Problem'|trans }}</p>
<p>{{ 'SignatureFormula'|trans }}</p>
<p>{{ _admin.name }} {{ _admin.surname }}<br>
    {{ 'Manager'|trans }} {{ _s.site_name }}<br>
    {{ _admin.telephone ? 'T. ' ~ _admin.telephone }}<br>
    {{ _admin.email ? 'Email'|trans ~ ': ' ~ _admin.email }}</p>

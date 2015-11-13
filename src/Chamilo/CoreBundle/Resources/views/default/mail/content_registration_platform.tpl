<p>{{ 'Dear'|trans }} {{ complete_name }},</p>
<p>{{ 'YouAreReg'|trans }} {{ _s.site_name }} {{ 'WithTheFollowingSettings'|trans }}</p>
<p>{{ 'Username'|trans }} : {{ login_name }}<br>
    {{ 'Pass'|trans }} : {{ original_password }}</p>
<p>{{ 'ThanksForRegisteringToSite'|trans|format(_s.site_name) }}</p>
<p>{{ 'Address'|trans }} {{ _s.site_name }} {{ 'Is'|trans }}
    : {{ mailWebPath }}</p>
<p>{{ 'Problem'|trans }}</p>
<p>{{ 'SignatureFormula'|trans }}</p>
<p>{{ _admin.name }}, {{ _admin.surname }}<br>
    {{ 'Manager'|trans }} {{ _s.site_name }}<br>
    {{ _admin.telephone ? 'T. ' ~ _admin.telephone }}<br>
    {{ _admin.email ? 'Email'|trans ~ ': ' ~ _admin.email }}</p>

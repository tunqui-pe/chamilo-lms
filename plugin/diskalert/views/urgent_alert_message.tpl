
{% if info.free_percent <= 20 %}
    {% set color_alert = '#f44336' %}
{% elseif info.free_percent <= 50 %}
    {% set color_alert = '#ffc107' %}
{% else %}
    {% set color_alert = '#009688' %}
{% endif %}

<div class="alerts-disk">
    <div class="plugin_logo">
        <img alt="" width="100px" src="{{ _p.web }}plugin/diskalert/assets/img/ssd.png">
    </div>
    <div id="percent">
        <p>{{ 'UrgentNoticeDiskSpace' |get_plugin_lang('DiskAlertPlugin') }}</p>
        <p>{{ 'YourAvailableDiskSpace'|get_plugin_lang('DiskAlertPlugin') }}</p>
        <h1 style="font-size: 4rem; padding: 0; margin: 0; text-align: center; color: {{ color_alert }}">
            {{  info.free_percent }} %
        </h1>
    </div>
    <p>{{ 'SystemStatisticsRecorded'|get_plugin_lang('DiskAlertPlugin') }} <strong>{{ date }}</strong></p>
</div>
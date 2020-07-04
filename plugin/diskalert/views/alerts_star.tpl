{% if info.free_percent <= 20 %}
    {% set color_alert = 'danger' %}
{% elseif info.free_percent <= 50 %}
    {% set color_alert = 'warning' %}
{% else %}
    {% set color_alert = 'success' %}
{% endif %}
{% if info.free_percent <= 20 %}
    {% set color_text = '#f44336' %}
{% elseif info.free_percent <= 50 %}
    {% set color_text = '#ffc107' %}
{% else %}
    {% set color_text = '#009688' %}
{% endif %}

<div class="panel panel-default">
    <div class="panel-heading">
        {{ 'SystemSpaceInformation'|get_plugin_lang('DiskAlertPlugin') }}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="alerts-canvas text-center">
                    <canvas id="pie_total_disk"></canvas>
                    <div class="alerts-actions">
                        <a href="{{ _p.web_main }}admin/configure_plugin.php?name=alerts" class="btn btn-default">
                            <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                            {{ 'ConfigurePlugin'|get_plugin_lang('DiskAlertPlugin') }}
                        </a>
                        <a href="{{ _p.web_main }}admin/settings.php" class="btn btn-default">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            {{ 'ConfigureAdministratorMail'|get_plugin_lang('DiskAlertPlugin') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alerts-info-disk">
                    <div class="plugin_logo">
                        <img alt="" width="80px" style="margin-bottom: 1rem;" src="{{ _p.web }}plugin/diskalert/assets/img/ssd.svg">
                        <p>{{ 'YourAvailableDiskSpace'|get_plugin_lang('DiskAlertPlugin') }}</p>
                        <h1 style="margin:0; color: {{ color_text }};">{{ info.free_percent }} %</h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td>{{ 'TotalDiskSpace'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="success">{{ info.total_disk }}</td>
                        </tr>
                        <tr>
                            <td>{{ 'UsedSpace'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="success">{{ info.used_disk }}</td>
                        </tr>
                        <tr>
                            <td>{{ 'AvailableSpace'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="{{ color_alert }}">{{ info.free_disk }}</td>
                        </tr>
                        <tr>
                            <td>{{ 'PercentageAvailable'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="{{ color_alert }}">{{ info.free_percent }} %</td>
                        </tr>
                        <tr>
                            <td>{{ 'PercentageUsed'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="success">{{ info.used_percent }} %</td>
                        </tr>
                        <tr>
                            <td>{{ 'EmailAlert'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="info">
                                {% if alert_email %}
                                    {{ 'ActivateEmailAlerts'|get_plugin_lang('DiskAlertPlugin') }}
                                {% else %}
                                    {{ 'DisabledEmailAlerts'|get_plugin_lang('DiskAlertPlugin') }}
                                {% endif %}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ 'AlertPercentage'|get_plugin_lang('DiskAlertPlugin') }}</td>
                            <td class="info">{{ percent_disk }} %</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

        <script type="text/javascript">
            var totalDiskCanvas = document.getElementById("pie_total_disk");
            Chart.defaults.global.defaultFontFamily = "Lato";
            Chart.defaults.global.defaultFontSize = 12;
            var freePercent = {{ info.free_percent }};
            var usedPercent = {{ info.used_percent }};
            var totalDiskData = {
                labels: [
                    "{{ 'Available'|get_plugin_lang('DiskAlertPlugin') }}",
                    "{{ 'Used'|get_plugin_lang('DiskAlertPlugin') }}"
                ],
                datasets: [{
                    data: [freePercent, usedPercent],
                    backgroundColor: [
                        "#ffcc54",
                        "#4cc0bf",
                    ]
                }]
            };
            var pieChart = new Chart(totalDiskCanvas, {
                type: 'pie',
                data: totalDiskData,
                responsive: true,
                options: {
                    title: {
                        display: true,
                        text: '{{ 'DiskUsageGraph'|get_plugin_lang('DiskAlertPlugin') }}',
                        padding: 20,
                        fontSize: 18,
                        fontStyle: 'bold'
                    }
                }
            });

        </script>
    </div>
</div>
{% if records %}
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>{{ 'RecordNo'|get_plugin_lang('DiskAlertPlugin') }}</th>
                <th>{{ 'RegistrationDate'|get_plugin_lang('DiskAlertPlugin') }}</th>
                <th>{{ 'UsedSpace'|get_plugin_lang('DiskAlertPlugin') }}</th>
                <th>{{ 'FreeSpace'|get_plugin_lang('DiskAlertPlugin') }}</th>
                <th>{{ 'PercentageOfDikUsage'|get_plugin_lang('DiskAlertPlugin') }}</th>
                <th>{{ 'Actions'|get_plugin_lang('DiskAlertPlugin') }}</th>
            </tr>
            </thead>
            <tbody>

            {% for record in records %}
                <tr>
                    <th scope="row">{{ record.id }}</th>
                    <td>{{ record.date_records }}</td>
                    <td>{{ record.disk_space_used }}</td>
                    <td>{{ record.disk_space_free }}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar"
                                 aria-valuenow="{{ record.percent_disk_used }}" aria-valuemin="0" aria-valuemax="100"
                                 style="width: {{ record.percent_disk_used }}%;">
                                {{ record.percent_disk_used }}%
                            </div>
                        </div>
                    </td>
                    <td>
                        <a title="{{ 'DeleteRecord'|get_plugin_lang('DiskAlertPlugin') }}" href="admin.php?action=delete&id={{ record.id }}"
                           class="btn btn-default btn-sm">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
{% endif %}



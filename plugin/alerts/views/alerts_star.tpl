
<div class="panel panel-default">
    <div class="panel-heading">
        Información de espacio del sistema
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="alerts-canvas text-center">
                    <canvas id="pie_total_disk"></canvas>
                    <div class="alerts-actions">
                        <a href="{{ _p.web_main }}admin/configure_plugin.php?name=alerts" class="btn btn-default">
                            <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                            Configurar plugin
                        </a>
                        <a href="{{ _p.web_main }}admin/settings.php" class="btn btn-default">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            Configurar correo de administrador
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alerts-info-disk">
                    <div class="plugin_logo">
                        <img alt="" width="80px" src="{{ _p.web }}plugin/alerts/assets/img/ssd.svg">
                    </div>
                    <table class="table">
                        <tr>
                            <td>Total espacio en disco</td>
                            <td class="success">{{ info.total_disk }}</td>
                        </tr>
                        <tr>
                            <td>Espacio usado</td>
                            <td class="success">{{ info.used_disk }}</td>
                        </tr>
                        <tr>
                            <td>Espacio disponible</td>
                            <td class="success">{{ info.free_disk }}</td>
                        </tr>
                        <tr>
                            <td>Porcentaje disponible</td>
                            <td class="success">{{ info.free_percent }} %</td>
                        </tr>
                        <tr>
                            <td>Porcentaje usado</td>
                            <td class="success">{{ info.used_percent }} %</td>
                        </tr>
                        <tr>
                            <td>Alerta de email</td>
                            <td class="info">
                                {% if alert_email %}
                                    {{ 'ActivateEmailAlerts'|get_plugin_lang('AlertsPlugin') }}
                                {% else %}
                                    {{ 'DisabledEmailAlerts'|get_plugin_lang('AlertsPlugin') }}
                                {% endif %}
                            </td>
                        </tr>
                        <tr>
                            <td>Porcentaje de alerta</td>
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
                labels:[
                    "Disponible %",
                    "Usando %"
                ],
                datasets:[{
                    data: [freePercent,usedPercent],
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
                options : {
                    title:{
                        display: true,
                        text : 'Gráfico de uso de disco',
                        padding: 20,
                        fontSize: 18,
                        fontStyle: 'bold'
                    }
                }
            });

        </script>
    </div>
</div>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>N° Registro</th>
            <th>Fecha de registro</th>
            <th>Espacio Usado</th>
            <th>Espacio Libre</th>
            <th>Porcentaje de uso disco</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>25-06-2020 01:00</td>
            <td>29.9 GB</td>
            <td>6.7 GB</td>
            <td>
                <div class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        82%
                    </div>
                </div>
            </td>
            <td>
                <a title="Enviar registro" href="#" class="btn btn-default btn-sm">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                </a>
                <a title="Eliminar registro" href="#" class="btn btn-default btn-sm">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
</div>


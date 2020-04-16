<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 class="title-redirect">Haz seleccionado el siguiente metodo de pago</h3>
                <div class="payment-logo">
                    {% if(type == "servipag") %}
                        <img src="{{ _p.web }}plugin/buycourses/resources/img/servipag_big.png">
                    {% else %}
                        <img src="{{ _p.web }}plugin/buycourses/resources/img/webpay_big.png">
                    {% endif %}
                </div>

                <div class="alert alert-info" role="alert">
                    A continuación se direccionar automaticamente en 5 segundos a la página de pago, si un caso no sudece puede dar clic <a href="{{ urlredirect }}">aquí</a>
                </div>
            </div>
        </div>

    </div>
</div>
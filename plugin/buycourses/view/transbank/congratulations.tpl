{% if response %}
    <div class="congratulations">
        <div class="row">
            <div class="col-md-12">
                <h1 class="title-webpay">{{ 'Congratulations'|get_plugin_lang('BuyCoursesPlugin') }}</h1>
                <img src="{{ _p.web }}plugin/buycourses/resources/img/webpay_big.png">
                <p>{{ 'accessTheCourse'|get_plugin_lang('BuyCoursesPlugin') }}</p>
                <div class="buy-summary">
                    <a class="btn btn-success" href="{{ my_courses }}">
                        {{ 'CourseAccess'|get_lang }}
                    </a>
                    <a class="btn btn-info" href="{{ url_catalog }}">
                        {{ 'BuyOtherCourses'|get_plugin_lang('BuyCoursesPlugin')}}
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-center">
                    <img  alt="{{ 'Congratulations'|get_plugin_lang('BuyCoursesPlugin') }}" class="img-responsive img-webpay" src="{{ _p.web }}plugin/buycourses/resources/img/payment.png">
                </div>
            </div>
        </div>
    </div>
{% else %}
    <div class="congratulations">
        <div class="row">
            <div class="col-md-12">
                <h1 class="title-webpay">{{ 'TransactionWasCanceled'|get_plugin_lang('BuyCoursesPlugin') }}</h1>
                <img src="{{ _p.web }}plugin/buycourses/resources/img/webpay_big.png">
                <p>{{ 'TransactionDeclined'|get_plugin_lang('BuyCoursesPlugin') }}</p>
                <div class="buy-summary">
                    <a class="btn btn-info" href="{{ url_catalog }}">
                        {{ 'BuyOtherCourses'|get_plugin_lang('BuyCoursesPlugin')}}
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-center">
                    <img alt="{{ 'TransactionWasCanceled'|get_plugin_lang('BuyCoursesPlugin') }}" class="img-responsive img-webpay" src="{{ _p.web }}plugin/buycourses/resources/img/payment_cancel.png">
                </div>
            </div>
        </div>
    </div>
{% endif %}


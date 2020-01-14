{% if response == -1%}
    <div class="congratulations">
        <div class="row">
            <div class="col-md-6">
                <img alt="{{ 'TransactionWasCanceled'|get_plugin_lang('BuyCoursesPlugin') }}" class="img-responsive" src="{{ _p.web }}plugin/buycourses/resources/img/children2.jpg">
            </div>
            <div class="col-md-6">
                <h1>{{ 'TransactionWasCanceled'|get_plugin_lang('BuyCoursesPlugin') }}</h1>
                <img src="{{ _p.web }}plugin/buycourses/resources/img/webpay_big.png">
                <p>{{ 'TransactionDeclined'|get_plugin_lang('BuyCoursesPlugin') }}</p>
                <div class="buy-summary">
                    <a class="btn btn-danger" href="{{ url_catalog }}">
                        {{ 'BuyOtherCourses'|get_plugin_lang('BuyCoursesPlugin')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
{% else %}
    <div class="congratulations">
        <div class="row">
            <div class="col-md-6">
                <img alt="{{ 'Congratulations'|get_plugin_lang('BuyCoursesPlugin') }}" class="img-responsive" src="{{ _p.web }}plugin/buycourses/resources/img/children.jpg">
            </div>
            <div class="col-md-6">
                <h1>{{ 'Congratulations'|get_plugin_lang('BuyCoursesPlugin') }}</h1>
                <img src="{{ _p.web }}plugin/buycourses/resources/img/webpay_big.png">
                <p>{{ 'accessTheCourse'|get_plugin_lang('BuyCoursesPlugin') }}</p>
                <div class="buy-summary">
                    <a class="btn btn-success" href="{{ my_courses }}">
                        {{ 'CourseAccess'|get_lang }}
                    </a>
                    <a class="btn btn-danger" href="{{ url_catalog }}">
                        {{ 'BuyOtherCourses'|get_plugin_lang('BuyCoursesPlugin')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
{% endif %}


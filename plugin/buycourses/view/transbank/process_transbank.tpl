<div class="transbank">
    <div class="row">
        <div class="col-md-12">
            <div class="section-title-container">
                <h3 class="section-title">{{ 'PurchaseData'|get_plugin_lang('BuyCoursesPlugin') }}</h3>
            </div>
            {% if buying_session %}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img alt="{{ session.name }}" class="img-rounded img-responsive" src="{{ session.image ? session.image : 'session_default.png'|icon() }}">
                            </div>
                            <div class="col-md-8">
                                <h3 style="font-weight: bold;">{{ session.name }}</h3>

                                <p class="date">
                                    <strong>{{ 'OrderDate'|get_plugin_lang('BuyCoursesPlugin') }}:</strong>
                                    {{ session.dates.display }}
                                </p>

                                {% if session.description %}
                                    <div class="description">
                                        {{ session.description }}
                                    </div>
                                {% endif %}

                                <p class="order">
                                    <strong>{{ 'PurchaseOrder'|get_plugin_lang('BuyCoursesPlugin') }} :</strong> {{ buy_order }}
                                </p>

                                {% if session.item.is_international %}
                                    <p class="price">
                                        <strong>{{ 'Total'|get_plugin_lang('BuyCoursesPlugin')}} :</strong>
                                        {{  session.item.price_int_formatted }}
                                    </p>
                                {% else %}
                                    <p class="price">
                                        <strong>{{ 'Total'|get_plugin_lang('BuyCoursesPlugin')}} :</strong>
                                        {{ session.item.price_formatted }}
                                    </p>
                                {% endif %}

                            </div>
                        </div>
                    </div>
                </div>

            {% elseif buying_course %}



            {% endif %}

            {% if terms %}
                <div class="panel panel-default buycourse-panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ 'TermsAndConditions'|get_plugin_lang('BuyCoursesPlugin') }}</h3>
                    </div>
                    <div class="panel-body">
                        <form action="#">
                            <div class="form-group">
                                <textarea class="form-control" style="height: 250px;" readonly>{{ terms }}</textarea>
                            </div>
                            <div class="checkbox">
                                <label for="confirmTermsAndConditons">
                                    <input class="" type="checkbox" id="confirmTermsAndConditons" name="confirmTermsAndConditons">
                                    {{ 'IConfirmIReadAndAcceptTermsAndCondition'|get_plugin_lang('BuyCoursesPlugin') }}
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            {% endif %}
            <div class="buy-summary ">
                <div class="row">
                    <div class="col-md-6">
                        {{ 'PaymentProcessed'|get_plugin_lang('BuyCoursesPlugin') }}
                        <img width="130px" src="{{ _p.web }}plugin/buycourses/resources/img/webpay_transbank.png">
                    </div>
                    <div class="col-md-6 form-payment-transbank">
                        <form action="{{ form_action }}" method="post" class="form-inline" role="form">
                            <input type="hidden" name="token_ws" value="{{ token_ws }}" >
                            <button type="submit" class="btn btn-success"> {{ 'ContinuePayout'|get_plugin_lang('BuyCoursesPlugin') }}</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{ dump(sales) }}
<ul class="sales-pending">
    {% for sale in sales %}
        <li id="sale_{{ sale.id }}">
            <div class="panel panel-default card-sale">
                <div class="panel-body">
                    <div class="pull-right">
                        <a href="{{ _p.web_self ~ '?' ~ {'order': sale.id, 'action': 'cancel'}|url_encode() }}">{{ 'CancelPending' | get_plugin_lang('BuyCoursesPlugin')  }}</a>
                    </div>
                    <h5 class="title">{{ sale.product_name }}</h5>
                    <div class="date-register">
                        <strong>{{ 'OrderDate'| get_plugin_lang('BuyCoursesPlugin') }} :</strong> {{ sale.date }}
                    </div>
                    <div class="code-reference">
                        <strong>{{ 'OrderReference'| get_plugin_lang('BuyCoursesPlugin') }} : </strong> {{ sale.reference }}
                    </div>
                </div>
            </div>
        </li>
    {% endfor %}
</ul>
<div style="padding: 2rem;">
    <p>{{ 'helloUser'|get_plugin_lang('BuyCoursesPlugin') }} <strong>{{ user.firstname }}</strong> {{ 'howAreYour'|get_plugin_lang('BuyCoursesPlugin') }}</p>
    <p>{{ 'confirmWebPay'|get_plugin_lang('BuyCoursesPlugin') }}</p>

    <h3 style="padding: 5px; text-transform: uppercase; font-size: 16px; background-color: #e7ecf7; display: inline-block; width: 100%;">
        {{ 'Detailsofpayment'|get_plugin_lang('BuyCoursesPlugin') }}
    </h3>

    <table style="width: 100%;">
        <tr>
            <td style="font-weight: bold;">
                {{ 'Establishment'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ _s.site_name }} - {{ _s.institution }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'DateAndTime'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.transaction_date|api_convert_and_format_date(constant('DATE_FORMAT_LONG')) }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'AuthorizationCode'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.code_auth }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'PurchaseOrder'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.reference }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'PaymentType'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.payment_type }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'CardNumber'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                xxxx xxxx xxxx {{ sale.card_number }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'UserName'|get_lang }}
            </td>
            <td>
                {{ user.complete_name }} - ({{ user.email }})
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'ProductName'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.product }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'SalePrice'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.currency ~ ' ' ~ sale.price }}
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="text-align: left;">
                <p>
                    {{ 'LastGoodbye'|get_plugin_lang('BuyCoursesPlugin') }}
                    <br>
                    {{ _s.site_name }} - {{ _s.institution }}
                </p>
            </td>
            <td style="text-align: right;">
                <img src="{{ _p.web }}plugin/buycourses/resources/img/webpay-plus-integracion.png">
            </td>
        </tr>
    </table>


</div>
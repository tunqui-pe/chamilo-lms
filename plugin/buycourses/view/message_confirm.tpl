<div style="padding: 2rem;">
    <p>{{ 'TheNextPurchase'|get_plugin_lang('BuyCoursesPlugin') }}</p>
    <table>
        <tr>
            <td style="font-weight: bold;">
                {{ 'OrderDate'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.date|api_convert_and_format_date(constant('DATE_TIME_FORMAT_LONG_24H')) }}
            </td>
        </tr>

        <tr>
            <td style="font-weight: bold;">
                {{ 'OrderReference'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ sale.reference }}
            </td>
        </tr>

        <tr>
            <td style="font-weight: bold;">
                {{ 'UserName'|get_lang }}
            </td>
            <td>
                {{ user.complete_name }}
            </td>
        </tr>

        <tr>
            <td style="font-weight: bold;">
                {{ 'Course'|get_lang }}
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

</div>

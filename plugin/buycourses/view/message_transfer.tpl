<div style="padding: 2rem;">
    <p>{{ 'helloUser'|get_plugin_lang('BuyCoursesPlugin') }} <strong>{{ user.firstname }}</strong> {{ 'howAreYour'|get_plugin_lang('BuyCoursesPlugin') }}</p>
    <p>{{ 'toCompleteYourRegistration'|get_plugin_lang('BuyCoursesPlugin') }}</p>

    <table style="width: 100%;">
        <tr>
            <td style="font-weight: bold;">
                {{ 'Name'|get_lang }}
            </td>
            <td>
                {{ transfer_accounts.1.name }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'CurrentAccount'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ transfer_accounts.1.account }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'Rut'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                {{ transfer_accounts.1.swift }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'Headline'|get_plugin_lang('BuyCoursesPlugin') }}
            </td>
            <td>
                FUNDACIÓN EDUCHILE
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ 'Email'|get_lang }}
            </td>
            <td>
                karen@educacionchile.cl
            </td>
        </tr>
    </table>

    <p>{{ 'ResendProof'|get_plugin_lang('BuyCoursesPlugin') }}</p>
    <h3 style="text-transform: uppercase; font-size: 16px;">{{ 'registrationData'|get_plugin_lang('BuyCoursesPlugin') }}</h3>

    <table style="width: 100%;">
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

    <p>
        {{ 'regardsEducaChile'|get_plugin_lang('BuyCoursesPlugin') }}
    </p>
</div>

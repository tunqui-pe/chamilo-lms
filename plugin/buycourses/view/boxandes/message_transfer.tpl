<div style="padding: 2rem;">
    <p>{{ 'helloUser'|get_plugin_lang('BuyCoursesPlugin') }} <strong>{{ user.firstname }}</strong> {{ 'howAreYour'|get_plugin_lang('BuyCoursesPlugin') }}</p>
    <p>{{ 'CompleteRegisterBoxAndes'|get_plugin_lang('BuyCoursesPlugin') }}</p>

    <ul>
        <li>RUT</li>
        <li>Nombres</li>
        <li>Apellidos</li>
        <li>E-mail</li>
        <li>Nombre del curso o diplomado</li>
    </ul>

    <p>{{ 'ValidityInformationBoxAndes'|get_plugin_lang('BuyCoursesPlugin') }}</p>

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

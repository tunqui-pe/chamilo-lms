<style type="text/css">

    .btn.theme-switcher {
        width: 54px;
        height: 50px;
        line-height: 50px;
        display: block;
        font-size: 27px;
        border: 1px solid;
        border-right-width: 0;
        border-radius: 3px 0 0 3px;
        text-align: center;
        position: absolute;
        left: -54px;
        top: 25px;
        z-index: 55;
        padding: 0;
        background-color: #e73939;
        border: 1px solid #e73939;
    }

    #sence-options {
        position: fixed;
        top: 110px;
        right: -300px;
        z-index: 9999;
        width: 300px;
        transition: transform 0.5s ease;
        display: block;
    }

    #sence-options.active {
        transform: translateX(-300px);
        -ms-transform: translateX(-300px);
        -o-transform: translateX(-300px);
        -webkit-transform: translateX(-300px);
    }

    .theme-switcher .glyph-icon {
        width: 54px;
        height: 50px;
        line-height: 50px;
        display: block;
    }

    .icon-spin {
        animation: spin 2s infinite linear;
    }

    #sence-options.active #theme-switcher-wrapper {
        box-shadow: 0 4px 5px rgba(0, 0, 0, 0.3);
    }

    #theme-switcher-wrapper {
        background: #fff;
        width: 300px;
        max-height: 580px;
        padding: 0;
        border-bottom-left-radius: 3px;
        border-top-left-radius: 3px;
        position: relative;
        z-index: 60;
        -webkit-transition: transform 0.5s ease;
        -o-transition: transform 0.5s ease;
        transition: transform 0.5s ease;
    }

    #theme-switcher-wrapper .header {
        background: #113B6A;
        border-top: #113B6A solid 1px;
        border-bottom: #113B6A solid 1px;
        border-top-left-radius: 3px;
        text-transform: uppercase;
        padding: 13px 15px 10px;
        font-size: 12px;
        color: #FFFFFF;
        font-weight: bold;
    }

    .theme-color-wrapper {
        padding: 10px;
    }

    .theme-color-wrapper .dl-horizontal{
        margin-top: 2rem;
        font-size: 12px;
    }
    .theme-color-wrapper .dl-horizontal dt{
        text-align: left;
        width: 120px;
    }
    .theme-color-wrapper .dl-horizontal dd {
        margin-left: 140px;
    }
    @-moz-keyframes spin {
        0% {
            -moz-transform: rotate(0deg);
        }
        100% {
            -moz-transform: rotate(359deg);
        }
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(359deg);
        }
    }

    @-o-keyframes spin {
        0% {
            -o-transform: rotate(0deg);
        }
        100% {
            -o-transform: rotate(359deg);
        }
    }

    @keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(359deg);
            transform: rotate(359deg);
        }
    }

</style>


<div id="sence-options" class="user-options active">
    <a href="javascript:void(0);" class="btn btn-primary theme-switcher">
        <i class="glyph-icon fa fa-cog icon-spin" aria-hidden="true"></i>
    </a>
    <div id="theme-switcher-wrapper">
        <h5 class="header">{{ 'InfoUserSence'|get_plugin_lang('SencePlugin') }}</h5>
        <div class="theme-color-wrapper">
            <div class="plugin_logo text-center">
                <img alt="" class="img-responsive" width="200px" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
            </div>
            {% if sence %}
            <dl class="dl-horizontal">
                <dt>{{ 'RutOtecCompany'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ rut_otec }}</dd>
                <dt>{{ 'NameOtecCompany'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ company_name }}</dd>
                <dt>{{ 'CodeSence'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ sence.code_sence }}</dd>
                <dt>{{ 'CodeCourse'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ sence.code_course }}</dd>
                <dt>{{ 'RunStudentSence'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ sence.run_student }}</dd>
                <dt>{{ 'DateLoginSence'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ sence.date_login }}</dd>
                <dt>{{ 'TimeZoneSence'|get_plugin_lang('SencePlugin') }}</dt>
                <dd>{{ sence.time_zone }}</dd>
            </dl>
                <a href="{{ url_session }}" class="btn btn-danger btn-block">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    {{ 'ButtonLogout'|get_plugin_lang('SencePlugin') }}
                </a>
            {% endif %}
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('ready', function () {

        $(".theme-switcher").click(function () {
            $("#sence-options").toggleClass('active');
        });

    });
</script>
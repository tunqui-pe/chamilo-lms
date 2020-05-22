
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="plugin_logo">
                    <img alt="" class="img-responsive" src="{{ _p.web }}plugin/sence/resources/img/logo_sence.png">
                </div>

                <div class="alert alert-warning" role="alert">
                    {{ 'NotHaveAnAssociatedSenceCourseCode' | get_lang }}
                </div>

                <div class="tools text-center">
                    <a href="{{ url_add_sence }}" class="btn btn-primary">
                        <i class="fa fa-codepen" aria-hidden="true"></i>
                        {{ 'AssociateCodeSence'|get_plugin_lang('SencePlugin') }}
                    </a>
                </div>


            </div>
        </div>
    </div>
</div>


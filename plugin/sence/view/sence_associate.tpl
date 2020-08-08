<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ form_sence }}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('input[name=action_id]').on( 'change', function() {
            if( !$(this).prop('checked') ) {
                $('input[name=action_id]').removeAttr('checked');
            }
        });

    });
</script>
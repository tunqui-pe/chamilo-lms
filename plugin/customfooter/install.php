<?php

global $_configuration;

api_add_setting(
    @$_configuration['defaults']['customfooter_footer_left'],
    'customfooter_footer_left',
    'customfooter',
    'setting',
    'Plugins'
);
api_add_setting(
    @$_configuration['defaults']['customfooter_footer_right'],
    'customfooter_footer_right',
    'customfooter',
    'setting',
    'Plugins'
);

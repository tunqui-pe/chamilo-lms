<?php

$plugin = RssPlugin::create();
$rss = $plugin->get_rss();
$title = $plugin->get_block_title();
$title = $title ? "<h4>$title</h4>" : '';

$bullet = api_get_asset('plugins/rss/arrow-bullet.png');
$css = api_get_css('plugins/rss/rss.css');

if (empty($rss)) {
    echo Display::return_message(get_lang('NoRSSItem'), 'warning');
    return;
}

echo<<<EOT
<div class="well sidebar-nav rss">
     $css
    <style type="text/css" scoped="scoped">
        .gfg-listentry-highlight{
            background-image: url('$bullet');
        }
    </style>
    <div class="menusection">
        <script src="http://www.google.com/jsapi"></script>
        <script src="http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.js" type="text/javascript"></script>
        <script>
            google.load('feeds', '1');
            function OnLoad() {
                var feeds = [
                    {
                        url: '$rss'
                    }
                ];

                var options = {
                    stacked : true,
                    numResults : 5,
                    horizontal : false,
                    title : 'Nouvelles!'
                };

                new GFdynamicFeedControl(feeds, 'news', options);        
            }
            google.setOnLoadCallback(OnLoad);
        </script>
        $title
        <div id="news" class="" style="min-height:300px;"></div>
    </div>
</div>
EOT;

<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Component\Editor\CkEditor\Toolbar;

/**
 * TestProposedAnswer toolbar configuration
 *
 * @package Chamilo\CoreBundle\Component\Editor\CkEditor\Toolbar
 */
class TestProposedAnswer extends Basic
{
    /**
     * Get the toolbar config
     * @return array
     */
    public function getConfig()
    {        
        $config['toolbarCanCollapse'] = true;
        $config['toolbarStartupExpanded'] = false;
        $config['extraPlugins'] = $this->getPluginsToString();
        //$config['width'] = '100';
        //$config['height'] = '200';
        if (api_get_setting('editor.more_buttons_maximized_mode') != 'true') {
            $config['toolbar'] = $this->getNormalToolbar();
        } else {
            $config['toolbar_minToolbar'] = $this->getMinimizedToolbar();

            $config['toolbar_maxToolbar'] = $this->getMaximizedToolbar();
        }
        return $config;
    }

    /**
     * @return array
     */
    public function getConditionalPlugins()
    {
        $plugins = array();
        if (api_get_setting(
                'document.show_glossary_in_documents'
            ) == 'ismanual'
        ) {
            $plugins[] = 'glossary';
        }

        return $plugins;
    }

    /**
     * Get the toolbar configuration when CKEditor is maximized
     * @return array
     */
    protected function getMaximizedToolbar()
    {
        return $this->getNormalToolbar();
    }

    /**
     * Get the default toolbar configuration when the setting more_buttons_maximized_mode is false
     * @return array
     */
    protected function getNormalToolbar()
    {
        return [
            ['Bold', 'Subscript', 'Superscript'],
            ['Image', 'Link', 'Audio', 'Table', 'PasteFromWord'],
            ['Maximize', 'Source'],
        ];
    }

    /**
     * Get the toolbar configuration when CKEditor is minimized
     * @return array
     */
    protected function getMinimizedToolbar()
    {
        return $this->getNormalToolbar();
    }
}

<?php

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

//import joomla filesystem classes
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');

class JFormFieldConfigurator extends JFormField {

	public $type = 'Configurator';

	protected function getInput()
    {

        global $expose;

		// necessary Joomla! classes
		$uri    = JURI::getInstance();
		$db     = JFactory::getDBO();
        $app    = JFactory::getApplication();

		// variables from URL
		$tid    = $uri->getVar('id', '');
		$task   = $uri->getVar('config_task', '');
		$file   = $uri->getVar('config_file', '');

        if( $file !== '') $file = $file . '.json'; //if file name is given add its extension

        $path   = JPATH_ROOT . '/templates/' . getTemplate($tid) . '/configs/';

		// helping variables
		$redirectUrl    = $uri->root() . 'administrator/index.php?option=com_templates&view=style&layout=edit&id=' . $tid;
		$saveUrl        = $redirectUrl . '&config_task=save&config_file=' ;
		$loadUrl        = $redirectUrl . '&config_task=load&config_file=' ;

		// if the URL contains proper variables
		if($tid !== 'none' && is_numeric($tid) && $task !== '')
        {
			if($task == 'load')
            {
				if( JFile::exists( $path . $file) )
                {
					// Insert the params into DB
					$query = '
						UPDATE 
							#__template_styles
						SET	
							params = '. $db->quote( file_get_contents($path . $file) ) .'
						WHERE 
						 	id = '.$tid.'
						LIMIT 1
						';

					// Executing SQL Query
					$db->setQuery($query);
					$result = $db->query();

					// check the result
					if($result) {
                        // Redirect
						$app->redirect($redirectUrl, $file . JText::_('CONFIGURATOR_LOADED_AND_SAVED'), 'message');
					} else {
                        // Redirect
                        $app->redirect($redirectUrl, JText::_('CONFIGURATOR_SQL_ERROR'), 'error');
					}
				} else {
					// Redirect
					$app->redirect($redirectUrl, JText::_('CONFIGURATOR_SELECTED_FILE_DOESNT_EXIST'), 'error');
				}	
			}
            else if($task == 'save')
            {
				if( $file == '' )
                {
					$date = JDate::getInstance();
                    $file = $date->format('Ymd-His') . '.json';
				}

				// write it
				if( JFile::write( $path . $file , $this->getParams($tid)->params) )
                {
					$app->redirect($redirectUrl, JText::_('CONFIGURATOR_SAVED_AS') . $file . JText::_('CONFIGURATOR_SAVED_IN') . $path , 'message');
				} else {
					$app->redirect($redirectUrl, JText::_('CONFIGURATOR_FAILED_CHECK_PERM'), 'error');
				}
			}
		}

		// generate the select list
		$options = (array) $this->getOptions( $tid );

		$file_select = JHtml::_('select.genericlist', $options, 'name', '', 'value', 'text', '0', 'config-load-filename');

		// return the standard formfield output
		$html       = '';
        $class		= $this->element['class'];
        $wrapstart  = '<div class="clearfix '.$class.'">';
        $wrapend    = '</div>';

        //button
        $html .= "<p class='mod-chrome-button config-btn gradient hasTip' rel='#configurator-form' title='::".JText::_($this->description)."'><span>".JText::_('CONFIGURATOR_BTN_LABEL')."</span></p>";

		$html .= '<div id="configurator-form">';
            $html .= '<h3>' . JText::_('CONFIGURATOR_TITLE') . '</h3>';
            $html .= '<p class="alert-message">'. JText::_('CONFIGURATOR_MSG') .'</p>';

            $html .= '<div><span class="label">' . JText::_('CONFIGURATOR_LOAD_LABEL') . '</span>' . $file_select . '<a id="configurator-load" class="gradient" href="#">' . JText::_('CONFIGURATOR_LOAD_BTN') . '</a> </div>';

		    $html .= '<div><span class="label">' . JText::_('CONFIGURATOR_SAVE_LABEL') . '</span> <input type="text" id="configurator-save-filename" /><span style="margin-top: 5px;">.json</span> <a id="configurator-save" class="gradient" href="#">' . JText::_('CONFIGURATOR_SAVE_BTN') . ' </a></div>';

        $html .= '</div>';

		// Js
        $js = "

            var file, url = '';

            $('#configurator-save').on('click', function(e)
            {
                e.preventDefault();
                if ( file = $('#configurator-save-filename').val() )
                {
                    url  = '$saveUrl' + file ;
                    window.location = url;
                }else{

                    alert('" .JText::_('CONFIGURATOR_FILENAME_EMPTY_ERROR') ."');
                    return;
                }
            });

            $('#configurator-load').on('click', function(e)
            {
                e.preventDefault();
                file = $('#config-load-filename').val();
                url  = '$loadUrl' + file ;

                window.location = url;
            });

        ";
        $expose->addjQDom($js);

		// finish the output
        return $wrapstart . $html . $wrapend;
	}


	protected function getOptions( $tid ) {

        $filter     = '\.json$';
        $stripExt	= TRUE ;
		$options    = array();

        $path   = JPATH_ROOT . '/templates/' . getTemplate($tid) . '/configs/';

        //Create configs directory if not exist and make the default.json
        if( !JFolder::exists($path) )
        {
            JFolder::create($path);

            // Create a blank index.html file
            JFile::write( $path . '/index.html', $this);

            // Make the default.json file
            $file = 'default.json';
            JFile::write( $path . '/' . $file , $this->getParams($tid)->params );
        }

        // Get a list of files in the search path with the given filter.
        $files = JFolder::files($path, $filter);

        // Build the options list from the list of files.
        $options[] = JHtml::_('select.option', '0', JText::alt('CONFIGURATOR_SELECT_CONFIG_FILE', 'language'));
        if (is_array($files)) {
            foreach($files as $file) {

                // If the extension is to be stripped, do it.
                if ($stripExt) {
                    $file = JFile::stripExt($file);
                }
                $options[] = JHtml::_('select.option', $file, ucfirst($file));
            }
        }

		return array_merge($options);
	}

    /*
     * Method to execute db queries and get the first result from db as an object
     *
     * @param   int     $tid id of the current template
     * @return  mixed   The return value or null if the query failed.
     * */
    protected function getParams($tid)
    {
        $db = JFactory::getDbo();

        // get the settings from the database
        $query = '
            SELECT
                params AS params
            FROM
                #__template_styles
            WHERE
                id = '.$tid.'
            LIMIT 1
            ';
        // Executing SQL Query
        $db->setQuery($query);
        $row = $db->loadObject();

        return $row;

    }
}

/* EOF */
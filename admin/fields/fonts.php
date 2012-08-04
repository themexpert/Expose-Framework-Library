<?php
/**
 * @package     Expose
 * @version     3.0.1    Mar 19, 2011
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.filesystem.file');

class JFormFieldFonts extends JFormField{

    protected $type = 'Fonts';


    protected function getInput(){
        $html = '';
        $options = array();
        //get template id
        $id = JRequest::getInt('id');
        // Initialize some field attributes.
        $class = $this->element['class'];
        $selectClass = 'class="gfonts"';

        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

        $wrapstart  = '<div class="field-wrap fonts-list clearfix '.$class.'">';
        $wrapend    = '</div>';

        //create a google font list file on admin folder to avoid api call and slow down the admin panel
        $fileName = 'gfonts.txt';
        $path = JPATH_ROOT . '/templates/' . getTemplate($id). '/' . $fileName;
        //$path = realpath(dirname(dirname(__FILE__))) .'/'.$fileName;

        if(!JFile::exists($path) OR JFile::read($path) == NULL){
            $this->createFontList($path);
        }

        $data = JFile::read($path);
        $data = explode(',', $data);
        //add none
        $options[] = JHtml::_('select.option', '0', JText::alt('JOPTION_DO_NOT_USE',preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));

        foreach($data as $val){
            list($fontVal, $fontName) = explode('=', "$val=");
            $fontVal = str_replace(' ','+',$fontVal);
            $options[] = JHtml::_('select.option',$fontVal, $fontName);
        }
        //pop the last empty array
        array_pop($options);


        $html .= "<a href=\"http://www.google.com/webfonts\" target=\"_blank\">Check Google Font Directory</a><br/>";
        $html .= JHtml::_('select.genericlist', $options, $this->name , $selectClass, 'value', 'text', $this->value, $this->id);

        return $wrapstart . $pretext. $html . $posttext . $wrapend;
    }

    private function createFontList($path){
        //output buffer
        $buffer = '';

        //Google webfont api key. you can change it with yours to avoide api limit
        $apiKey = 'AIzaSyCCRvYb-KRB-uyWAgTDyrRRHxitVfK7aFE';
        $url = "https://www.googleapis.com/webfonts/v1/webfonts?key=$apiKey";

       // curl check
		if (!function_exists('curl_version')) {
			$msg = "<strong>cURL is required to load Google Fonts. </strong> Learn more at <a href='http://www.php.net/manual/en/book.curl.php'>http://www.php.net</a>";

			return "
				<div class='alert-message error'>
                    <p>$msg</p>
				</div>
			";
		}

        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        @curl_setopt($process, CURLOPT_MAXREDIRS, 20);
        $data = curl_exec($process);
        curl_close($process);
        $data = json_decode($data);

        $variants = array(
            'regular' => array('400'=> ''),
            '400'=>'',
            '400italic' => ' Italic',
            'italic' => array('400italic' => ' Italic'),
            '700' => ' Bold',
            'bold'=> array('700' => ' Bold'),
            '700italic' => ' Bold Italic',
            'bolditalic' => array('700italic' => ' Bold Italic')
        );

        foreach($data->items as $font){
            foreach($font->variants as $variant){
                if(array_key_exists($variant,$variants)){
                    if(is_array($variants[$variant])){
                        foreach($variants[$variant] as $key => $val){
                            $fontName = $font->family . $val;
                            $fontVal = $key;
                        }
                    }else{
                        $fontName = $font->family . $variants[$variant];
                        $fontVal = $variant;
                    }

                    //regular or 400 variation will only have the name
                    if($fontVal == 'regular' OR $fontVal == '400'){
                        $fontVal = $font->family;
                    }else{
                        $fontVal = $font->family . ':' . $fontVal;
                    }

                    $buffer .= "$fontVal=$fontName".',';
                }
            }
        }

        JFile::write($path,$buffer);

    }
}


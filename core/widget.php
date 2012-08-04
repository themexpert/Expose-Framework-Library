<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/

/**
 * Abstract class for Gists
 **/
abstract class ExposeWidget{

    protected $name = NULL;
    protected $enabled = NULL;
    protected $position = NULL;
    protected $mobile = NULL;


    public function isEnabled()
    {
        if(!isset($this->enabled))
        {
            $this->enabled = (int) $this->get('enabled');
        }

        return $this->enabled;
    }

    public function getPosition()
    {
        if(!isset($this->position))
        {
            $this->position = $this->get('position');
        }

        return $this->position;
    }

    public function isInPosition($position)
    {
        if ($this->getPosition() == $position) return TRUE;

        return FALSE;
    }

    public function isInMobile()
    {
        if(!isset($this->mobile))
        {
            $this->mobile = (int) $this->get('mobile',0);
        }

        return $this->mobile;
    }

    public function get($param,$default=NULL)
    {
        global $expose;

        $field = $this->name . '-' .$param;

        return $expose->get($field,$default);
    }

    public function init(){

    }

    public function render(){
        
    }


}

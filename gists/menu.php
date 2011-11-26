<?php

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.gist');

class ExposeGistMenu extends ExposeGist{

    public $name = 'menu';

    public function render()
    {

    }

}

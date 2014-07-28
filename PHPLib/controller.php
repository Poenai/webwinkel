<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 28-7-14
 * Time: 15:33
 */

class Controller {
    public $view;

    public function __construct($view)
    {
        $this->view = $view;

        $this->SetLayout();
    }

    public function SetLayout()
    {
        require dirname(__FILE__)."/layout/".str_replace("Controller", "", get_class($this)).".php" ;
    }

    public function SetView()
    {
        if(method_exists($this, $this->view))
        {
            $this->{$this->view}();

            require dirname(__FILE__)."/view/".str_replace("Controller", "", get_class($this))."/{$this->view}.php" ;
        }
        else
        {
            http_response_code(404);
        }
    }

} 
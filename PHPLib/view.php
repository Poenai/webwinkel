<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 28-7-14
 * Time: 18:54
 */

class View {

    /**
     * @var string
     */
    private  $_view;
    /**
     * @var mixed[]
     */
    private $_vars;
    /**
     * @var string
     */
    private $_controller;

    public function __construct($controller, $view)
    {
        $this->_controller = $controller;
        $this->_view = $view;


    }

    public function RenderPage()
    {
        $this->SetLayout();
    }

    /**
     * @param string $name
     * @param mixed $var
     */
    public function SetVar($name, $var)
    {
        $this->_vars[$name] = $var;
    }

    private  function SetLayout()
    {
        require dirname(__FILE__)."/layout/".$this->_controller.".php" ;
    }

    private function SetView()
    {
        if(isset($this->_vars))
        {
            foreach($this->_vars as $var => $val)
            {
                $$var = $val;
            }
        }

        require dirname(__FILE__)."/view/".$this->_controller."/{$this->_view}.php" ;

    }
} 
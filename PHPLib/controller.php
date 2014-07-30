<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 28-7-14
 * Time: 15:33
 */

require_once dirname(__FILE__)."/view.php";

class Controller {
    /**
     * @var View
     */
    private $_view;


    /**
     * @param $default
     */
    public function __construct($default)
    {
        if(isset($_GET['page']))
        {
            $view = chop($_GET['q'], '/');
        }else if(isset($_GET['q']))
        {
            $_GET['page'] = chop($_GET['q'], '/');
            $view = chop($_GET['q'], '/');
        }
        else
        {
            $view = $default;
        }


        if(method_exists($this, $view))
        {
            $this->_view = new View(str_replace("Controller", "", get_class($this)), $view);


            //$this->{$view}();

            call_user_func_array(array($this, $view), array());

            $this->_view->RenderPage();
        }
        else
        {
            http_response_code(404);
        }
    }

    /**
     * @param string $name
     * @param mixed $var
     */
    protected function SetVar($name, $var)
    {
        $this->_view->SetVar($name, $var);
    }

} 
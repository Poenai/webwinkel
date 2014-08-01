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
        $urlParts = array();
        if(isset($_GET['page']))
        {
            $_GET['q'] = $_GET['page'];

        }if(isset($_GET['q']))
        {
            // split de string eerst op bij een een '/' en verwijder daarna met array_filter alle lege waarde eruit
            $urlParts =  array_filter(explode('/', $_GET['q']));

            //eerste item in de array is de methode de rest zijn parameters voor de methode daarom wordt de eerste verwijderd zodat de rest kan worden gebruikt als parameters
            $view = $_GET['page'] = $urlParts[0];
            unset($urlParts[0]);

            //bepaal level voor de view
            $level = 0;
            for($i = 0; $i < strlen($_GET['q']);$i++)
            {
                if($_GET['q'][$i] == '/')
                    $level++;
            }
        }
        else{
            $view = $default;
            $level = 0;
        }

        if(method_exists($this, $view))
        {
            $this->_view = new View(str_replace("Controller", "", get_class($this)), $view);
            $this->_view->level = $level;

            call_user_func_array(array($this, $view), $urlParts);

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
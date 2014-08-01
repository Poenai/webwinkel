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

        if($view == "action")
        {
            $action = $urlParts[1];
            unset($urlParts[1]);
            $this->RequistAsAction($action, $urlParts);
        }elseif(method_exists($this, $view))
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
     * @var bool
     * @description standaard is de output van een verzoek maar desgewenst kan het worden omgezet naar xml dan worden de headers veranderd
     */
    private $_isJson = true;

    /**
     * @param boolean $isJson
     */
    public function setIsJson($isJson)
    {
        $this->_isJson = $isJson;
    }

    /**
     * @return boolean
     */
    public function getIsJson()
    {
        return $this->_isJson;
    }

    /**
     * @description Deze methode wordt gebruikt voor http verzoeken die normaal gesproken niet door mensen direct wordt gedaan om te kunnen bekijken maar wordt
     * meestal gebruikt door scripts om data te versturen en ontvangen
     * ze gaan in de urll altijd vooraf met action/methodenaam.
     * de methoden moet action_methode hete
     */
    private function RequistAsAction($action, $parameters)
    {
        try
        {
            $result = call_user_func_array(array($this, "action_".$action), $parameters);
        }
        catch(Exception $e)
        {
            $result = json_encode(array("error" => true, "message"=>$e->getMessage()));
            $this->setIsJson(true);
        }


        if($this->_isJson)
        {
            header('Content-Type: application/json');
        }
        echo $result;
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
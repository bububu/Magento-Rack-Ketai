<?php
class Rack_Ketai_Helper_Agent
{
    protected $_agent;
    protected $_isAdmin = false;

    public function __construct()
    {
        $uri = Mage::app()->getRequest();
        if(preg_match("/extensions_(custom|local)/i", $uri->getRequestUri())) {
            $this->_isAdmin = true;
        }
        if(!$this->_isAdmin){
            $error = error_reporting(E_ALL);
            $include_path = get_include_path();
            set_include_path($include_path . PS . BP . DS . 'lib/PEAR');
            require_once('Net/UserAgent/Mobile.php');
            $this->_agent = Net_UserAgent_Mobile::singleton();
        }
    }

    public function getCookieEnable()
    {
        $cookie = true;
        if(!$this->_isAdmin){
            switch(true)
            {
            case ($this->_agent->isDoCoMo()) :
                $cookie = $this->dispatchBrowser();
                break;
            case ($this->_agent->isEzweb()) :
            case ($this->_agent->isSoftbank()) :
            default :
                $cookie = true;
                break;
            }
        }
        return $cookie;
    }

    public function isMobile()
    {
        if(!$this->_isAdmin){
            switch(true)
            {
                case ($this->_agent->isDoCoMo()) :
                case ($this->_agent->isEzweb()) :
                case ($this->_agent->isSoftbank()) :
                    return true;
                default :
                    return false;
            }
        }
        return false;
    }

    protected function dispatchBrowser()
    {
        if(!$this->_isAdmin){
            if($this->_agent->getBrowserVersion() == "2.0")
            {
                return true;
            }
        }
        return false;
    }
}

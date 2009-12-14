<?php
class Rack_Ketai_Model_Cookie extends Mage_Core_Model_Cookie
{
    public function get($name = null)
    {
        $error = error_reporting(E_ALL);
        $include_path = get_include_path();
        set_include_path($include_path . PS . BP . DS . 'lib/PEAR');
        require_once('Net/UserAgent/Mobile.php');
        
        $agent = Net_UserAgent_Mobile::singleton();

        switch(true)
        {
            case ($agent->isDocomo()) :
            case ($agent->isVodafone()) :
            case ($agent->isEzweb()) :
                error_reporting($error);
                return true;
            default:
                return $this->_getRequest()->getCookie($name, false);
        }
    }
}

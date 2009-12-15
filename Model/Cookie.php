<?php
class Rack_Ketai_Model_Cookie extends Mage_Core_Model_Cookie
{
    public function get($name = null)
    {
        $agent = Mage::helper('ketai/agent');
        if(!$agent->getCookieEnable())
        {
            return true;
        }
        
        return $this->_getRequest()->getCookie($name, false);
    }
}

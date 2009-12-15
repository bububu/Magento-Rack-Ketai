<?php
class Rack_Ketai_Model_Design extends Mage_Core_Model_Design_Package
{
    
    public function setTheme()
    {
        $agent = Mage::helper('ketai/agent');

        if ($agent->isMobile()) {
            foreach (array('layout', 'template', 'skin', 'locale') as $type) {
    			$this->_theme[$type] = "ketai";
    		}
			return $this;
        } else {
                switch (func_num_args()) {
	             case 1:
    		            foreach (array('layout', 'template', 'skin', 'locale') as $type) {
    			        $this->_theme[$type] = func_get_arg(0);
    		            }
    			    break;
	             case 2:
			        $this->_theme[func_get_arg(0)] = func_get_arg(1);
			        break;
	             default:
	                throw Mage::exception(Mage::helper('core')->__('Wrong number of arguments for %s', __METHOD__));
        		}       
         }	
    }
}

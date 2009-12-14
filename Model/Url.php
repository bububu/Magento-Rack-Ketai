<?php
class Rack_Ketai_Model_Url extends Mage_Core_Model_Url
{
    /**
     * Build url by requested path and parameters
     *
     * @param   string $routePath
     * @param   array $routeParams
     * @return  string
     */
    public function getUrl($routePath=null, $routeParams=null)
    {
        $escapeQuery = false;
        
        $error = error_reporting(E_ALL);
        $include_path = get_include_path();
        set_include_path($include_path . PS . BP . DS . 'lib/PEAR');
        require_once('Net/UserAgent/Mobile.php');
        
        $agent = Net_UserAgent_Mobile::singleton();

        /**
         * All system params should be unseted before we call getRouteUrl
         * this method has condition for ading default controller anr actions names
         * in case when we have params
         */
        if (isset($routeParams['_fragment'])) {
            $this->setFragment($routeParams['_fragment']);
            unset($routeParams['_fragment']);
        }

        if (isset($routeParams['_escape'])) {
            $escapeQuery = $routeParams['_escape'];
            unset($routeParams['_escape']);
        }

        $query = null;
        if (isset($routeParams['_query'])) {
            $query = $routeParams['_query'];
            unset($routeParams['_query']);
        }

        $noSid = null;
        if (isset($routeParams['_nosid'])) {
            $noSid = (bool)$routeParams['_nosid'];
            unset($routeParams['_nosid']);
        }
        $url = $this->getRouteUrl($routePath, $routeParams);
        /**
         * Apply query params, need call after getRouteUrl for rewrite _current values
         */
        if ($query !== null) {
            if (is_string($query)) {
                $this->setQuery($query);
            } elseif (is_array($query)) {
                $this->setQueryParams($query, !empty($routeParams['_current']));
            }
            if ($query === false) {
                $this->setQueryParams(array());
            }
        }

        
        switch(true)
        {
            case ($agent->isDocomo()) :
            case ($agent->isVodafone()) :
            case ($agent->isEzweb()) :
                error_reporting($error);
                $this->_prepareSessionUrl($url);
                break;
            default:
                if ($noSid !== true) {
                    $this->_prepareSessionUrl($url);
                }
                break;
        }

        if ($query = $this->getQuery($escapeQuery)) {
            $url .= '?'.$query;
        }

        if ($this->getFragment()) {
            $url .= '#'.$this->getFragment();
        }

        return $this->escape($url);
    }

    /**
     * Check and add session id to URL
     *
     * @param string $url
     * @return Mage_Core_Model_Url
     */
    protected function _prepareSessionUrl($url)
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
                $session = Mage::getSingleton('core/session');
                if ($this->getSecure()) {
                    $this->setQueryParam('___SID', 'S');
                }
                else {
                    $this->setQueryParam('___SID', 'U');
                }
                $this->setQueryParam($session->getSessionIdQueryParam(), SID);                
                break;
            default:
                if (!$this->getUseSession()) {
                    return $this;
                }
        
                $session = Mage::getSingleton('core/session');
                /* @var $session Mage_Core_Model_Session */
                if (Mage::app()->getUseSessionVar()) {
                    // secure URL
                    if ($this->getSecure()) {
                        $this->setQueryParam('___SID', 'S');
                    }
                    else {
                        $this->setQueryParam('___SID', 'U');
                    }
                }
                else {
                    if ($sessionId = $session->getSessionIdForHost($url)) {
                        $this->setQueryParam($session->getSessionIdQueryParam(), $sessionId);
                    }
                }
                break;
        }
        return $this;
    }
    
}

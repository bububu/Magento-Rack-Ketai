<?php
class Rack_Ketai_Block_Core_Messages extends Mage_Core_Block_Messages
{
    /**
     * Retrieve messages in HTML format grouped by type
     *
     * @param   string $type
     * @return  string
     */
    public function getGroupedMobileHtml()
    {
        $types = array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::SUCCESS
        );
        $html = '';
        foreach ($types as $type) {
            if ( $messages = $this->getMessages($type) ) {

                foreach ( $messages as $message ) {
                    $html.= ($this->_escapeMessageFlag) ? $this->htmlEscape($message->getText()) : $message->getText();
                    $html.="<br/>";
                }
            }
        }
        return $html;
    }

    protected function _toHtml()
    {
        return $this->getGroupedHtml();
    }
}

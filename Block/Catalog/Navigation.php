<?php
class Rack_Ketai_Block_Catalog_Navigation extends Mage_Catalog_Block_Navigation
{
    /**
     * Enter description here...
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int $level
     * @param boolean $last
     * @return string
     */
    public function drawItemSingle($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        if(!$last) {
            $html .= '┗';
        } else {
            $html .= '┣';
        }    
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a><br/>';

        return $html;
    }
}

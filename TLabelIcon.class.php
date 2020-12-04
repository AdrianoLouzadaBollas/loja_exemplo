<?php
/**
 * TLabelIcon
 * @author  Adriano Louzada Bollas infobitsolucoes.com>
 */
class TLabelIcon extends TLabel
{
    public function __construct($text,$textColor = null,$color = null,$icon = null)
    {
      
      $color = isset($color) ? $color : 'red'; 
      $icon = isset($icon) ? $icon : 'triangle';  
      
      $arr_icon = array('triangle' => "<i class = 'fa fa-exclamation-triangle {$color}'></i>",
                         'exclamation' => "<i class = 'fa fa-exclamation {$color}'></i>",
                         'circle' => "<i class = 'fa fa-exclamation-circle {$color}'></i>" );   
      
      
      $text_label = $arr_icon[$icon] . ' ' . $text;
      parent::__construct($text_label, $textColor);          
    }
    
    
    public function show()
    {
     parent::setTip(_t('Mandatory filling'));
     parent::show();     
    }
}


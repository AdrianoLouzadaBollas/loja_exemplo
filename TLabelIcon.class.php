<?php
/**
 * TLabelIcon
 * @author  Adriano Louzada Bollas infobitsolucoes.com>
 */
class TLabelIcon extends TLabel
{
    
    public function __construct($icon,$text,$color = null)
    {
      
      !$color?$color = 'red':'';      
      
      $arr_aviso = array('triangulo' => "<i class='fa fa-exclamation-triangle {$color}'></i>",
                         'exclamacao'=> "<i class='fa fa-exclamation {$color}'></i>",
                         'circulo'   => "<i class='fa fa-exclamation-circle {$color}'></i>");
                         
      $tx_label = $arr_aviso[$icon] . ' '. $text;          
                         
      parent::__construct($tx_label);
    }
    
    
    public function show()
    {
     parent::setTip('Preenchimento Obrigatório');
     parent::show();     
    }
}


<?php
/**
 * ComboChange
 * @author  Adriano Louzada Bollas
 */
class ComboChange extends TPage
{
    protected $form; // form
    
    public function __construct( $param )
    {
        parent::__construct();
        
        $itens = array();
        for($i=1;$i<=20;$i++)
        {
            $itens[] = 'Vários Itens  ' . $i;
        }
        
        $this->form = new BootstrapFormBuilder('form_Produtos');
        $this->form->setFormTitle('Combo Change');
        
        $comboTipo  = new TCombo('comboTipo');
        $comboTipo->addItems([0=>'Apenas um',1=>'Vários']);
        $comboTipo->setDefaultOption(false);
        $comboTipo->setChangeAction(new TAction([$this,'onChangeTipo']));
        $comboTipo->id = 'comboTipo';
        
        $comboItens = new TCombo('comboItens');
        $comboItens->id = 'comboItens';
        $comboItens->addItems($itens);
        $comboItens->setDefaultOption(false);
        
        $campoTeste = new TEntry('campoTeste');
        
        $script  = '$("#comboTipo").trigger("change");';
        $script .= "$('#comboItens').on('select2:select', 
                       function (e) {altura = $('.select2-selection--multiple').height();
                                     if(altura>34)
                                        {
                                            $('.colTipo').css('min-height',altura+34);
                                            $('.colTeste').css('min-height',altura+34);
                                        }else{
                                                  $('.colTipo').css('min-height',34);
                                                  $('.colTeste').css('min-height',34);
                                             }
                                        
                                    });";
        
        $script .= "$('#comboItens').on('select2:unselect', 
                       function (e) {
                                        altura = $('.select2-selection--multiple').height();
                                        if(altura>34)
                                        {
                                             $('.colTipo').css('min-height',altura+34);
                                             $('.colTeste').css('min-height',altura+34);
                                        }else{
                                                  $('.colTipo').css('min-height',34);
                                                  $('.colTeste').css('min-height',34);
                                             }
                                    });";                                     
        //TScript::create($script);
        
        $row = $this->form->addFields([new TLabel('Tipo'),$comboTipo],
                                      [new TLabel('Itens'),$comboItens],
                                      [new TLabel('Teste'),$campoTeste]);
        $row->layout = ['col-md-4 colTipo','col-md-4 col-itens','col-md-4 colTeste'];
        $row->class = 'col-itens';

        /*
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:download black');
        $btn->class = 'btn btn-sm btn-success';
        */
        
        $comboTipo->setSize('100%');
        $comboItens->setSize('100%');
        $campoTeste->setSize('100%');
 
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    public static function onChangeTipo($param)
    {
        $tipo = $param['comboTipo'];
        
        $arrayScript[0]  = "$('#comboItens').select2({multiple: false,data: ''}).val('').change();";
        $arrayScript[0] .= "$('.colTipo').css('min-height',34);";
        $arrayScript[0] .= "$('.colTeste').css('min-height',34);";
         
        $arrayScript[1] = "$('#comboItens').select2({multiple: true, data: ''}).val('').change();";
        
        TScript::create($arrayScript[$tipo]);
    }

    public function onSave($param)
    {

    }
}

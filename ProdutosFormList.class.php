<?php
/**
 * ProdutosFormList Form List
 * @author  Adriano Louzada Bollas
 */
class ProdutosFormList extends TPage
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    protected $loaded;
    
    use Adianti\Base\AdiantiFileSaveTrait;
    
    public function __construct( $param )
    {
        parent::__construct();
        
        
        $this->form = new BootstrapFormBuilder('form_Produtos');
        $this->form->setFormTitle('Cadastro de Produtos');

        $styleId = 'font-weight:bold;text-align:center;';
        
        $produtoId           = new TEntry('produtoId');
        $produtoId->style = $styleId;
        
        $produtoNome         = new TEntry('produtoNome');
        $produtoNome->forceUpperCase();
        $produtoNome->addValidation('<b>Produto</b>', new TRequiredValidator);
        
        $produtoImagem       = new TFile('produtoImagem');
        $produtoImagem->enableImageGallery('100','100');
        $produtoImagem->setAllowedExtensions( ['gif', 'png', 'jpg', 'jpeg'] );
        $produtoImagem->enableFileHandling();
        
        $produtoTamanho      = new TEntry('produtoTamanho');
        $produtoTamanho->style = $styleId;
        $produtoTamanho->addValidation('<b>Tamanho/Qtd.</b>', new TRequiredValidator);
        
        $produtoMedida       = new TDBCombo('produtoMedida', 'db_loja', 'Medidas', 'medidaId', 'medidaDescricao');
        $produtoMedida->setDefaultOption(false);
        $produtoMedida->id = 'produtoMedida';
        
        $produtoCor          = new TDBCombo('produtoCor', 'db_loja', 'Cores', 'corId', 'corDescricao');
        $produtoCor->setDefaultOption(false);
        $produtoCor->id = 'produtoCor';
        
        $produtoGenero       = new TDBCombo('produtoGenero', 'db_loja', 'Generos', 'generoId', 'generoDescricao');
        $produtoGenero->setDefaultOption(false);
        $produtoGenero->id = 'produtoGenero';
        
        $produtoMarca        = new TDBCombo('produtoMarca', 'db_loja', 'Marcas', 'marcaId', 'marcaDescricao');
        $produtoMarca->setDefaultOption(false);
        $produtoMarca->id = 'produtoMarca';
        
        $produtoModelo       = new TDBCombo('produtoModelo', 'db_loja', 'Modelos', 'modeloId', 'modeloDescricao');
        $produtoModelo->setDefaultOption(false);
        $produtoModelo->id = 'produtoModelo';
        
        $produtoDetalhes     = new TEntry('produtoDetalhes');
        
        $produtoPrecoCusto   = new TEntry('produtoPrecoCusto');
        $produtoPrecoCusto->setNumericMask(2, ',', '.', true);
        $produtoPrecoCusto->addValidation('<b>Preço de Custo</b>', new TRequiredValidator);
        
        $produtoPrecoVenda   = new TEntry('produtoPrecoVenda');
        $produtoPrecoVenda->setNumericMask(2, ',', '.', true);
        $produtoPrecoVenda->addValidation('<b>Preço de Venda</b>', new TRequiredValidator);
        
        $produtoQtdAtual     = new TEntry('produtoQtdAtual');
        $produtoQtdAtual->setMask('9!');
        $produtoQtdAtual->style = $styleId;
        $produtoQtdAtual->addValidation('<b>Qtd. Atual</b>', new TRequiredValidator);
        $produtoQtdAtual->setValue(0);
        
        $produtoQtdMinima    = new TEntry('produtoQtdMinima');
        $produtoQtdMinima->setMask('9!');
        $produtoQtdMinima->style = $styleId;
        $produtoQtdMinima->addValidation('<b>Qtd. Min.</b>', new TRequiredValidator);
        $produtoQtdMinima->setValue(0);
        
        $produtoQtdMaxima    = new TEntry('produtoQtdMaxima');
        $produtoQtdMaxima->setMask('9!');
        $produtoQtdMaxima->style = $styleId;
        $produtoQtdMaxima->addValidation('<b>Qtd. Max.</b>', new TRequiredValidator);
        $produtoQtdMaxima->setValue(0);
        
        $tempDataCadastro = new TEntry('tempDataCadastro');
        $tempDataCadastro->setEditable(false);
        $tempDataCadastro->style = $styleId;
        
        //Links        
        $icone         = '<i class="fa fa-plus-circle green red"></i>';
        $btStyle       = 'margin:-3px;font-size:1em;border:none;background:none;';
        
        //Botão Cores
        $botaoCores = new TButton("botaoCores");
        $botaoCores->setAction(new TAction([$this,'mostrarJanelaLateral'],
                               ['janela'=>'CoresFormList','comboNome'=>'produtoCor','comboModel'=>'Cores',
                                'comboChave'=>'corId','comboMostrar'=>'corDescricao']),"Cor {$icone}");
        $botaoCores->style = $btStyle;
        $botaoCores->addStyleClass('label');
        
        //Botão Gêneros
        $botaoGeneros = new TButton("botaoGeneros");
        $botaoGeneros->setAction(new TAction([$this,'mostrarJanelaLateral'],
                               ['janela'=>'GenerosFormList','comboNome'=>'produtoGenero','comboModel'=>'Generos',
                                'comboChave'=>'generoId','comboMostrar'=>'generoDescricao']),"Gênero {$icone}");
        $botaoGeneros->style = $btStyle;
        $botaoGeneros->addStyleClass('label');
        
        //Botão Medidas
        $botaoMedidas = new TButton("botaoMedidas");
        $botaoMedidas->setAction(new TAction([$this,'mostrarJanelaLateral'],
                               ['janela'=>'MedidasFormList','comboNome'=>'produtoMedida','comboModel'=>'Medidas',
                                'comboChave'=>'medidaId','comboMostrar'=>'medidaDescricao']),"Un. Medida {$icone}");
        $botaoMedidas->style = $btStyle;
        $botaoMedidas->addStyleClass('label');
        
        
        //Botão Marcas
        $botaoMarcas = new TButton("botaoMarcas");
        $botaoMarcas->setAction(new TAction([$this,'mostrarJanelaLateral'],
                                           ['janela'=>'MarcasFormList','comboNome'=>'produtoMarca',
                                           'comboModel'=>'Marcas','comboChave'=>'marcaId',
                                           'comboMostrar'=>'marcaDescricao']),"Marca {$icone}");
        $botaoMarcas->style = $btStyle;
        $botaoMarcas->addStyleClass('label');
        
        //Botão Modelos
        $botaoModelos = new TButton("botaoModelos");
        $botaoModelos->setAction(new TAction([$this,'mostrarJanelaLateral'],
                                           ['janela'=>'ModelosFormList','comboNome'=>'produtoModelo',
                                           'comboModel'=>'Modelos','comboChave'=>'modeloId',
                                           'comboMostrar'=>'modeloDescricao']),"Modelo {$icone}");
        $botaoModelos->style = $btStyle;
        $botaoModelos->addStyleClass('label');
        
        $row = $this->form->addFields( [new TLabel('ID') , $produtoId ] ,
                                       [ new TLabelIcon('Produto','black','red','circle') , $produtoNome ] );
        $row->layout = ['col-md-2','col-md-10'];                               
        
        //$row = $this->form->addFields( [ new TLabel('Imagem') ], [ $produtoImagem ] );
        
        
        $row = $this->form->addFields( [ $botaoMedidas, $produtoMedida ] ,
                                       [ new TLabelIcon('Tamanho/Qtd.','black','red','circle'), $produtoTamanho ] ,
                                       [ $botaoCores,$produtoCor ],
                                       [ $botaoGeneros, $produtoGenero ] );
        $row->layout = ['col-md-3','col-md-3','col-md-3','col-md-3'];
                                       
        $row = $this->form->addFields( [ $botaoMarcas, $produtoMarca ] ,
                                       [ $botaoModelos, $produtoModelo ], 
                                       [ new TLabelIcon('Preço de Custo','black','red','circle') , $produtoPrecoCusto ],
                                       [ new TLabelIcon('Preço de Venda','black','red','circle') , $produtoPrecoVenda ] );
        $row->layout = ['col-md-3','col-md-3','col-md-3','col-md-3'];
                                       
        $row = $this->form->addFields( [ new TLabelIcon('Qtd. Atual','black','red','circle') , $produtoQtdAtual ] ,
                                       [ new TLabelIcon('Qtd. Mín.','black','red','circle') , $produtoQtdMinima ] ,
                                       [ new TLabelIcon('Qtd. Max.','black','red','circle') , $produtoQtdMaxima ],
                                       [ new TLabel('Data Cadastro') , $tempDataCadastro ]);
        $row->layout = ['col-md-3','col-md-3','col-md-3','col-md-3'];
                                       
        $row = $this->form->addFields( [ new TLabel('Detalhes') , $produtoDetalhes ]);
        $row->layout = ['col-md-12'];
        //$row = $this->form->addFields( [ new TLabel('Busca') ], [ $produtoBusca ] );

        // set sizes
        $produtoId->setSize('100%');
        $produtoNome->setSize('100%');
        $produtoImagem->setSize('100%');
        $produtoTamanho->setSize('100%');
        $produtoMedida->setSize('100%');
        $produtoCor->setSize('100%');
        $produtoGenero->setSize('100%');
        $produtoMarca->setSize('100%');
        $produtoModelo->setSize('100%');
        $produtoDetalhes->setSize('100%');
        $produtoPrecoCusto->setSize('100%');
        $produtoPrecoVenda->setSize('100%');
        $produtoQtdAtual->setSize('100%');
        $produtoQtdMinima->setSize('100%');
        $produtoQtdMaxima->setSize('100%');
        $tempDataCadastro->setSize('100%');
        
        if (!empty($produtoId))
        {
            $produtoId->setEditable(FALSE);
        }
        
        $btn = $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:plus-circle');
        $btn->class = 'btn btn-sm btn-secondary';
        
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-success';
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');

        // creates the datagrid columns
        $column_produtoId           = new TDataGridColumn('produtoId', 'ID', 'center','5%');
        $column_produtoNome         = new TDataGridColumn('produtoNome', 'PRODUTO', 'left','30%');
        $column_produtoTamanho      = new TDataGridColumn('produtoTamanho', 'TAMANHO', 'center','20%');
        $column_produtoPrecoCusto   = new TDataGridColumn('produtoPrecoCusto', 'CUSTO', 'right');
        $column_lucro               = new TDataGridColumn('produtoLucro','LUCRO(%)','center');
        $column_produtoPrecoVenda   = new TDataGridColumn('produtoPrecoVenda', 'VENDA', 'right');
        $column_produtoQtdAtual     = new TDataGridColumn('produtoQtdAtual', 'ESTOQUE', 'center');
        $column_produtoQtdMinima    = new TDataGridColumn('produtoQtdMinima', 'EST. MIN.', 'center');
        $column_produtoQtdMaxima    = new TDataGridColumn('produtoQtdMaxima', 'EST. MAX.', 'center');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_produtoId);
        $this->datagrid->addColumn($column_produtoNome);
        $this->datagrid->addColumn($column_produtoTamanho);
        $this->datagrid->addColumn($column_produtoQtdAtual);
        $this->datagrid->addColumn($column_produtoPrecoCusto);
        $this->datagrid->addColumn($column_lucro);
        $this->datagrid->addColumn($column_produtoPrecoVenda);
        //$this->datagrid->addColumn($column_produtoQtdMinima);
        //$this->datagrid->addColumn($column_produtoQtdMaxima);
        
        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onEdit']);
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        $action1->setLabel(_t('Edit'));
        $action1->setImage('far:edit blue');
        $action1->setField('produtoId');
        
        $action2 = new TDataGridAction([$this, 'onDelete']);
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action2->setLabel(_t('Delete'));
        $action2->setImage('far:trash-alt red');
        $action2->setField('produtoId');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    public function carregarDados($param)
    {
        $dados = TSession::getValue('form_produtos');
        TSession::delValue('form_produtos');
             
        $formName = $this->form->getName();
        
        TDBCombo::reloadFromModel($formName, $dados->comboNome, 
        'db_loja', $dados->comboModel, $dados->comboChave, 
        $dados->comboMostrar, $dados->comboMostrar);
        
        $this->form->setData($dados);
        
        if($param['key'])
        {
            $key = $param['key'];
            $dados->{$dados->comboNome} = $key;
            $script  = "$('#{$dados->comboNome}').find('option[value=\"{$key}\"]').attr('selected',true);";
            TScript::create($script);
        }
               
    }
    
    public function mostrarJanelaLateral($param)
    {
        $dados  = $this->form->getData();
             
        $dados->janela       = $param['janela'];
        $dados->comboNome    = $param['comboNome'];
        $dados->comboChave   = $param['comboChave'];
        $dados->comboMostrar = $param['comboMostrar'];
        $dados->comboModel   = $param['comboModel'];
        
        TSession::setValue('form_produtos',$dados);   
             
        $script = "__adianti_load_page('engine.php?class={$dados->janela}&method=onEdit');";
        TScript::create($script);                   
    }


    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'db_loja'
            TTransaction::open('db_loja');
            
            // creates a repository for Produtos
            $repository = new TRepository('Produtos');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order'])) 
            {
                $param['order'] = 'produtoId';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid 
                    $object->produtoLucro = (($object->produtoPrecoVenda*100)/$object->produtoPrecoCusto)-100;    
                    $object->produtoLucro = number_format($object->produtoLucro, 2, '.', '');   
                               
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key = $param['key']; // get the parameter $key
            TTransaction::open('db_loja'); // open a transaction with database
            $object = new Produtos($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Save form data
     * @param $param Request
     */
    public function onSave($param)
    {
        try
        {
            TTransaction::open('db_loja'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Produtos;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
           
            $object->store(); // save the object
            
            // copia o arquivo para a pasta de destino, e atualiza o objeto
            $this->saveFile($object, $data, 'produtoImagem', 'files/imgProdutos');
            
            // get the generated produtoId
            $data->produtoId = $object->produtoId;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved')); // success message
            $this->onReload(); // reload the listing
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('db_loja'); // open a transaction
                $object = new Produtos($key); // instantiates the Active Record
                $object->tempDataCadastro = TDate::date2br($object->produtoDataCadastro);
                
                //$object->tempDataCadastro = ($object->produtoDataCadastro);
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}

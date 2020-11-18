<?php
/**
 * CoresFormList Form List
 * @author  Adriano Louzada Bollas
 */
class CoresFormList extends TPage
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    protected $loaded;

    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');

        $this->form = new BootstrapFormBuilder('form_Cores');
        $this->form->setFormTitle('Cadastro de Cores');
        
        $styleId = 'font-weight:bold;text-align:center';

        $corId = new TEntry('corId');
        $corId->style = $styleId;
        
        $corDescricao = new TEntry('corDescricao');
        $corDescricao->forceUpperCase();

        $row = $this->form->addFields( [ new TLabel('ID') , $corId ],
                                       [ new TLabel('COR') , $corDescricao ] );
        $row->layout = ['col-md-3','col-md-9'];
        
        $corId->setSize('100%');
        $corDescricao->setSize('100%');

        if (!empty($corId))
        {
            $corId->setEditable(FALSE);
        }
        
        $btn = $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:plus-circle');
        $btn->class = 'btn btn-sm btn-secondary';
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-success';
        
        //Botão Fechar do Formulário
        //$this->form->addHeaderActionLink( _t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        $this->form->addHeaderAction( _t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');

        $column_corId = new TDataGridColumn('corId', 'ID', 'center');
        $column_corDescricao = new TDataGridColumn('corDescricao', 'DESCRIÇÃO', 'left');

        $this->datagrid->addColumn($column_corId);
        $this->datagrid->addColumn($column_corDescricao);

        $action1 = new TDataGridAction([$this, 'onEdit']);
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        $action1->setLabel(_t('Edit'));
        $action1->setImage('far:edit blue');
        $action1->setField('corId');
        
        $action2 = new TDataGridAction([$this, 'onDelete']);
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action2->setLabel(_t('Delete'));
        $action2->setImage('far:trash-alt red');
        $action2->setField('corId');
        
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
    
    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('db_loja');

            $repository = new TRepository('Cores');
            $limit = 10;

            $criteria = new TCriteria;

            if (empty($param['order']))
            {
                $param['order'] = 'corId';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->datagrid->addItem($object);
                }
            }

            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public static function onDelete($param)
    {
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead

        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    public static function Delete($param)
    {
        try
        {
            $key = $param['key']; // get the parameter $key
            TTransaction::open('db_loja'); // open a transaction with database
            $object = new Cores($key, FALSE); // instantiates the Active Record
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
    
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('db_loja'); // open a transaction
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Cores;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated corId
            $data->corId = $object->corId;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
 
            TToast::show('success', 'Registro Salvo', 'bottom right', 'far:check-circle' );            
            $this->onReload(); // reload the listing
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
 
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
 
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('db_loja'); // open a transaction
                $object = new Cores($key); // instantiates the Active Record
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
    
    public static function onClose($param)
    {
        $key = $param['corId'];
        TScript::create("Template.closeRightPanel()");
        $script = "__adianti_load_page('engine.php?class=ProdutosFormList&method=carregarDados&key={$key}');";
        TScript::create($script);                
    }
    
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}

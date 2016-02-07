<?php
/**
 *  Name:    Blocks
 *  Type:    Controller
 *  About:   Management blocks
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Blocks;

class Controller extends \Control\ControllerAncestor
{
    protected $conf;
    protected $em;
    
    private $rep;
    
    public function __construct() {
        parent::__construct();
        
        $this->rep = $this->em->getrepository('Blocks\Entities\Block');
    }
    
    // List of blocks
    public function listAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Блоки');
        $form->setTemplate('list');
        
        $form->addHead(array(
            array('width' => 1),
            array('width' => 400, 'align' => 'left', 'text' => 'Название'),
            array('width' => 1),
            array('width' => 1)
        ));
        
        $rows = array();
        
        $blocks = $this->rep->findBy(array());
        foreach ($blocks as $block)
        {
            $edit = array(
                'type' => 'link_img_edit',
                'href' => '#Blocks/edit/'. $block->id
            );
            
            $title = array(
                'type' => 'link',
                'href' => '#Blocks/edit/'. $block->id,
                'text' => $block->title
            );
            
            $active = ($block->active == 1)
                ? array(
                    'type' => 'link_img_active',
                    'action' => "disable_item('Blocks','". $block->id ."');"
                )
                : array(
                    'type' => 'link_img_disable',
                    'action' => "activate_item('mail','". $block->id ."');"
                )
            ;
            
            $delete = array(
                'type'   => 'link_img_delete',
                'action' => "javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('mail','". $block->id ."'); }"
            );
            
            $rows[] = array($edit, $title, $active, $delete);
        }
        
        $form->addRows($rows);
        
        return $form->render();
    }
    
    // Options
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title'=>'Добавить блок', 'href'=>'#Blocks/add');
        $json[] = array('title'=>'Список блоков', 'href'=>'#Blocks/list');
        
        return json_encode($json);
    }
    
    // The form of adding block
    public function addAction()
    {
        $data = new \Data\Data;
        $data->setName('block');
        $output = $data->addForm(array('title' => 'Добавить блок'));
        
        return $output;
    }
    
    // The form of editing block
    public function editAction($id)
    {
        $data = new \Data\Data;
        $data->setName('block');
        $output = $data->editForm(array('title' => 'Редактировать блок'), $id);
        
        return $output;
    }
    
    // Maintaining or adding a block
    public function saveAction($entity, $id = null)
    {
        $data = array();
        
        $fields = explode($this->conf->ac_sep, $_POST['fields']);
        $values = explode($this->conf->ac_sep, $_POST['values']);
        
        foreach($fields as $key => $field)
            $data[$field] = $values[$key];
        
        $block = new \Blocks\Block;
        $block->save($data, $id);
    }
    
    // Deleting a block
    public function deleteAction($entity, $id)
    {
        $id = intval($id);
        
        $block = new \Blocks\Block;
        $block->delete($id);
    }
    
    // Enabling a block
    public function activateAction($entity, $id)
    {
        $id = intval($id);
        
        $block = new \Blocks\Block;
        $block->save(array('active' => 1), $id);
    }
    
    // Disabling a block
    public function disableAction($entity, $id)
    {
        $id = intval($id);
        
        $block = new \Blocks\Block;
        $block->save(array('active' => 0), $id);
    }
}
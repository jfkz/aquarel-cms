<?php
/**
 * Module:  Blocks
 * About:   Модель данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Blocks;

class Block extends \Control\Model
{
    private $repBlock;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Blocks\Entities\Block');
    }
    
    /* ToDo
     *
     * Добавить вменяемое условие, на каких страницах выводить те или иные блоки
     */
    public function getList()
    {
        $output = array();
        
        $blocks = $this->repository->findBy(
            array('active' => 1),
            array('weight' => 'ASC')
        );
        
        foreach ($blocks as $block)
        {
            $output[] = array(
                'name'   => $block->name,
                'module' => $block->module,
                'region' => $block->position,
            );
        }
        
        /*if(count($live->_request) == 1 && empty($live->_request[0]))
        {
            $output[] = array(
                'name'   => 'main',
                'module' => 'News',
                'region' => 'center'
            );
        }*/
        
        return $output;
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
            $item = new \Blocks\Entities\Block;
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}
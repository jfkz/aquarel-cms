<?php
/**
 * Class:   Events
 * File:    class.events.php
 * Authors: Vlad Andreev <php.vlad@gmail.com>, Mikhail Pershin <merzkaya.babka@gmail.com>
 **/

namespace Events;

class Controller extends \Control\ControllerAncestor
{
    public function __construct($event = null)
    {
        parent::__construct();
        
        $this->repAction = $this->em->getRepository('Events\Entities\Action');
        $this->repEvent = $this->em->getRepository('Events\Entities\Event');
        $this->repMonitor = $this->em->getRepository('Events\Entities\Monitor');
    }
    
    // Список событиий, действий и связей
    public function listAction($type = 'events')
    {
        if ($type == 'events')
        {
            $form_title = 'События';
            $items = $this->repEvent->findBy(array());
        }
        elseif($type == 'actions')
        {
            $form_title = 'Действия';
            $items = $this->repAction->findBy(array());
        }
        
        $form = new \Control\Forms;
        $form->setTitle($form_title);
        $form->setTemplate('list');
        $form->addHead(array(
            array('width'=>30, 'align'=>'left', 'text'=>'№'),
            array('width'=>400, 'align'=>'left', 'text'=>'Имя'),
            array('width'=>400, 'align'=>'left', 'text'=>'Идентификатор'),
        ));
        
        $rows = array();
        
        foreach ($items as $item)
        {
            $row = array(
                array(
                    'type'   => 'html',
                    'text'   => $item->id
                ),
                array(
                    'type'   => 'html',
                    'text'   => $item->title
                ),
                array(
                    'type'   => 'html',
                    'text'   => $item->name
                ),
            );
            
            $rows[] = $row;
        }
        
        $form->addRows($rows);
        $output = $form->render();
        
        return $output;
    }
    
    // Список связей
    public function monitorAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Монитор');
        $form->setTemplate('list');
        $form->addHead(array(
            array('width'=>400, 'align'=>'left', 'text'=>'Событие'),
            array('width'=>400, 'align'=>'left', 'text'=>'Действия')
        ));
        
        $rows = array();
        
        // Связи событие->действия
        $actions = array();
        $links = $this->repMonitor->findBy(array(), array('event' => 'ASC', 'weight' => 'ASC'));
        foreach ($links as $link)
        {
            $action = $this->repAction->findOneBy(array('name' => $link->action, 'module' => $link->module));
            
            if(!empty($action))
                $actions[$link->event][] = $action->title .' ('. $link->module .'::'. $link->action .')';
        }
        
        // События
        $events = $this->repEvent->findBy(array());
        foreach ($events as $event)
        {
            $events[$event->name] = array('title' => $event->title, 'actions' => array());
            
            if (count($actions[$event->name]) > 0)
                $rows[] = array($event->title .' ('. $event->name .')', implode('<br/>', $actions[$event->name]));
        }
        
        $form->addRows($rows);
        $output = $form->render();
        
        return $output;
    }
    
    // Список опций
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title'=>'Список связей', 'href'=>'#Events/monitor');
        $json[] = array('title'=>'Список событий', 'href'=>'#Events/list/events');
        $json[] = array('title'=>'Список действий', 'href'=>'#Events/list/actions');
        // $json[] = array('title'=>'Настройки', 'href'=>'#Events/settings');
        
        $output = json_encode($json);
        
        return $output;
    }
}
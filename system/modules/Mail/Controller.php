<?php
/**
 *  Class:   Mail
 *  Type:    Controller
 *  About:   Отправка писем
 *  Authors: Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Mail;

class Controller extends \Control\ControllerAncestor
{
    private $repMail;
    
    public function __construct() {
        parent::__construct();
        
        $this->repMail = $this->em->getrepository('Mail\Entities\Mail');
    }
    
    // Отправка писем
    public static function send($event)
    {
        $conf = core::conf();
        $db   = core::db();
        
        // Поиск шаблона письма по имени события
        $template = $db->Execute(sprintf("SELECT `subject`,`text`,`tags` FROM {$conf->db_prefix}mail WHERE `event`='%s' AND `active`='1'", $event))->fields;
        $subject    = htmlspecialchars_decode($template['subject']);
        $message    = htmlspecialchars_decode($template['text']);
        $tags       = explode(',',$template['tags']);
        
        // Замена тегов в письме на значения переменных
        foreach ($tags as $tag)
        {
            $tag = explode('.',$tag);
            
            if(is_array($GLOBALS[$tag[0]])) $value = $GLOBALS[$tag[0]][$tag[1]];
            elseif(is_object($GLOBALS[$tag[0]])) $value = $GLOBALS[$tag[0]]->$tag[1];
            else $value = $GLOBALS[$tag[0]];
           
            $subject = str_replace('{'.$tag[0].'.'.$tag[1].'}', $value, $subject);
            $message = str_replace('{'.$tag[0].'.'.$tag[1].'}', $value, $message);
        }
        
        $mail_to = $user->email;
        $mail_from = $conf->site_mail;
        
        // Отправка письма
        if($subject != '' && $message != '')
        {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: '.$user->name.' <'.$mail_to.'>' . "\r\n";
            $headers .= 'From: '.$conf->site_name.' <'.$mail_from.'>' . "\r\n";
            mail ($mail_to, $subject, $message, $headers);
        }
    }
    
    // Список писем
    public function listAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Письма');
        $form->setTemplate('list');
        
        $form->addHead(array(
            array('width' => 1),
            array('width' => 400, 'align' => 'left', 'text' => 'Название'),
            array('width' => 1),
            array('width' => 1)
        ));
        
        $rows = array();
        
        $mails = $this->repMail->findBy(array());
        foreach ($mails as $mail)
        {
            $edit = array(
                'type' => 'link_img_edit',
                'href' => '#Mail/edit/'. $mail->id
            );
            
            $title = array(
                'type' => 'link',
                'href' => '#Mail/edit/'. $mail->id,
                'text' => $mail->title
            );
            
            $active = ($mail->active == 1) ?
                array(
                    'type' => 'link_img_active',
                    'action' => "disable_item('mail','". $mail->id ."');"
                ) :
                array(
                    'type' => 'link_img_disable',
                    'action' => "activate_item('mail','". $mail->id ."');"
                );
            
            $delete = array(
                'type'   => 'link_img_delete',
                'action' => "javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('mail','". $mail->id ."'); }"
            );
            
            $rows[] = array($edit, $title, $active, $delete);
        }
        
        $form->addRows($rows);
        
        return $form->render();
    }
    
    // Список опций
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title'=>'Добавить письмо', 'href'=>'#Mail/add');
        $json[] = array('title'=>'Список писем', 'href'=>'#Mail/list');
        
        return json_encode($json);
    }
    
    // Форма добавления письма
    public function addAction()
    {
        $data = new \Data\Data;
        $data->setName('mail');
        $output = $data->addForm(array('title' => 'Добавить письмо'));
        
        return $output;
    }
    
    // Форма редактирования письма
    public function editAction($id)
    {
        $data = new \Data\Data;
        $data->setName('mail');
        $output = $data->editForm(array('title' => 'Редактировать письмо'), $id);
        
        return $output;
    }
    
    public function saveAction($data_name, $id = null)
    {
        $data = array();
        
        $fields = explode($this->conf->ac_sep, $_POST['fields']);
        $values = explode($this->conf->ac_sep, $_POST['values']);
        
        foreach($fields as $key => $field)
            $data[$field] = $values[$key];
        
        $mail = new \Mail\Mail;
        $mail->save($data, $id);
    }
    
    // Удалить
    public function deleteAction($data_name, $id)
    {
        $id = intval($id);
        
        $mail = new \Mail\Mail;
        $mail->delete($id);
    }
    
    
    // Включить
    public function activateAction($data_name, $id)
    {
        $id = intval($id);
        
        $mail = new \Mail\Mail;
        $mail->activate($id);
    }
    
    // Выключить
    public function disableAction($data_name, $id)
    {
        $id = intval($id);
        
        $mail = new \Mail\Mail;
        $mail->disable($id);
    }
}
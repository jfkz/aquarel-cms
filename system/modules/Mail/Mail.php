<?php
/**
 * Class:   Mail
 * About:   Отправка писем
 * Authors: Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Mail;

class Mail extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Mail\Entities\Mail');
    }
    
    // Отправить письма
    public static function send($event)
    {
        $live = \Control\Live::getInstance();
        $conf = \Control\Core::conf();
        
        // Поиск шаблона письма по имени события
        $mail = $this->find(array('event' => $event, 'active' => 1));
        
        if (!empty($mail))
        {
            // Замена тегов в письме на значения переменных
            $tags = explode(',', $mail->tags);
            foreach ($tags as $tag)
            {
                $tag = explode('.', $tag);
                
                if (is_array($GLOBALS[$tag[0]]))
                    $value = $GLOBALS[$tag[0]][$tag[1]];
                elseif (is_object($GLOBALS[$tag[0]]))
                    $value = $GLOBALS[$tag[0]]->$tag[1];
                else
                    $value = $GLOBALS[$tag[0]];
                
                $subject = str_replace('{'. $tag[0] .'.'. $tag[1] .'}', $value, $mail->subject);
                $message = str_replace('{'. $tag[0] .'.'. $tag[1] .'}', $value, $mail->message);
            }
            
            $mail_to = $user->email;
            $mail_from = $conf->site_mail;
            
            // Отправка письма
            if(!empty($subject) && !empty($message))
            {
                $headers = 'MIME-Version: 1.0'."\r\n";
                $headers.= 'Content-type: text/html; charset=utf-8'."\r\n";
                $headers.= 'To: '. $live->user->name .' <'. $mail_to .'>'."\r\n";
                $headers.= 'From: '. $conf->site_name .' <'. $mail_from .'>'."\r\n";
                
                mail ($mail_to, $subject, $message, $headers);
            }
        }
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Mail\Entities\Mail;
            
            $item->weight = 0;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}
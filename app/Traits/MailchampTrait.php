<?php

namespace App\Traits;
use App\Models\Order;


trait MailchampTrait
{

    public function sendMail($id)
    {

        $order = Order::find($id);
        $listId = 'b1342cfcff';
        $mailchimp = new \Mailchimp('630b2dcb3014340a5a648f498ef935d1-us5');

        $options = [

            'list_id'   => $listId,
            'subject' => 'order notification',
            'from_name' => 'ahmedelgaml90',
            'from_email' => 'ahmedelgaml936@gmail.com',
            'to_name' => 'ahmedelgaml90@gmail.com',
        ];

        $content = [

           'html' =>" تم استقبال طلب جديد برقم "." ".$order->order_number." "."from user"." ".$order->user->name,
           'text' => strip_tags(" تم استقبال طلب جديد برقم"." ".$order->order_number.""."from user"." ".$order->user->name)
        ];

        $campaign = $mailchimp->campaigns->create('regular', $options, $content);
        $mailchimp->campaigns->send($campaign['id']);
    
   }
   

}

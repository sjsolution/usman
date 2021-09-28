<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Emailtemplates;
use App\Models\User;

class AdminInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userdata,$pwd)
    {
        $this->user=$userdata;
        $this->password = $pwd;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $template_obj=Emailtemplates::where('slug','congratulation-service-provider')->first();
      if($template_obj) {
          $search=explode(",", $template_obj->replace_vars);
          $name = $this->user['name'];
          $email = $this->user['email'];
          $password = $this->password;
          $activation = config('app.url');
          //$activation = config('app.url').'activation/'.$this->user['remember_token'];
          $replace = [$name,$email,$password,$activation];
          $template_html=str_replace($search, $replace, $template_obj->body);
          return $this->subject($template_obj->subject)->view('emails.common',['html_body'=>$template_html]);
      }
    }
}

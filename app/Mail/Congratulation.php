<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Emailtemplates;
use App\Models\User;
class Congratulation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userdata)
    {
        $this->user=$userdata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $template_obj=Emailtemplates::where('slug','congratulation')->first();
      if($template_obj) {
          $search=explode(",", $template_obj->replace_vars);
          $name = $this->user['full_name_en'];
          $email = $this->user['email'];
          $replace = [$name,$email];
          $template_html=str_replace($search, $replace, $template_obj->body);
          return $this->subject($template_obj->subject)->view('emails.common',['html_body'=>$template_html]);
      }
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Emailtemplates;
use App\Models\User;
class Forgotpassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }
    public function __construct($user,$token)
    {

      $this->reset_token = $token;
      $this->userdata = $user;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $template_obj=Emailtemplates::where('slug','forgot-password')->first();
      if($template_obj) {
          //$url = encrypt($this->userdata->id).'-'.time();
          $search=explode(",", $template_obj->replace_vars);
          $name = $this->userdata['full_name_en'];
          $url = url('/').'/resetPassword/'.$this->reset_token;
          $replace = [$name,$url];
          $template_html=str_replace($search, $replace, $template_obj->body);
          return $this->subject($template_obj->subject)->view('emails.common',['html_body'=>$template_html]);
      }
    }
}

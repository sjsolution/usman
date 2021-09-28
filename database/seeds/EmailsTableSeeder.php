<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class EmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->insert([
            'from' => "info@maak.live",
            'subject' => "User Signup",
            'body'=>"Hello {{name}},<br> Congratulation your account has been created successfully <br><br><br><br>Regards <br>Maak Team",
            'replace_vars'=>"{{name}},{{email}}",
            'slug'=>"congratulation",
        ]);

        DB::table('email_templates')->insert([
          'from' => "info@maak.live",
          'subject' => "Invitation",
          'body'=>"Hello {{name}},<br>Congratulation your account has been created successfully <br>Your Email Address : {{email}},<br>Your Password : {{password}} <br><br>Please login your account <a href='{{activation}}' class='btn btn-success'>Login Now </a><br><br><br><br><br>Regards <br>Maak Team",
          'replace_vars'=>"{{name}},{{email}},{{password}},{{activation}}",
          'slug'=>"congratulation-service-provider",
        ]);

        DB::table('email_templates')->insert([
          'from' => "info@maak.live",
          'subject' => "Forgot Password",
          'body'=>"Hello {{name}},<br>Your password reset link is below <br><a href='{{url}}' class='btn btn-success'>Verify Now </a><br><br><br><br><br>Regards <br>Maak Team",
          'replace_vars'=>"{{name}},{{url}}",
          'slug'=>"forgot-password",
        ]);
        DB::table('email_templates')->insert([
          'from' => "info@maak.live",
          'subject' => "Invitation",
          'body'=>"Hello {{name}},<br>Congratulation your account has been created successfully <br>Your Email Address : {{email}},<br>Your Password : {{password}} <br><br><br><br><br><br><br>Regards <br>Maak Team",
          'replace_vars'=>"{{name}},{{email}},{{password}}",
          'slug'=>"congratulation-technician",
        ]);
    }
}

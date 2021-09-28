<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('users', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('email',50)->unique();
          $table->string('password')->nullable();
          $table->string('full_name_en')->nullable();
          $table->string('full_name_ar')->nullable();
          $table->tinyInteger('user_type')->default('1')->comment('1=service provider,2=users,3=customer,4=technician');
          $table->date('date_of_birth')->nullable();
          $table->tinyInteger('gender')->default('1')->comment('1=male,2=female,3=transgender');
          $table->string('platform')->nullable()->comment('app,facebook,googleplus,linkedin,twitter');
          $table->string('facebook_id')->nullable()->comment('facebook id');
          $table->string('google_id')->nullable()->comment('googleplus id');
          $table->string('linkedin_id')->nullable()->comment('linkedin id');
          $table->string('twitter_id')->nullable()->comment('twitter id');
          $table->string('phone_number',20)->nullable();
          $table->string('country_code')->nullable();
          $table->string('mobile_number',20)->nullable();
          $table->string('temp_country_code')->default(0);
          $table->string('temp_mobile_number',20)->default(0);
          $table->longText('address')->nullable();
          $table->enum('is_bestseller',['0','1'])->default('0')->comment('1=is bestseller,0=not bestseller');
          $table->longText('about')->nullable();
          $table->string('otp')->nullable();
          $table->string('token_expiration_time')->nullable();
          $table->string('password_reset_token')->nullable();
          $table->string('profile_pic')->nullable();
          $table->tinyInteger('is_verified_email')->default('0')->comment('1=email verified,0=email unverified');
          $table->tinyInteger('is_verified_phone')->default('0')->comment('1=phone verified,0=phone unverified');
          $table->tinyInteger('is_verified_mobile')->default('0')->comment('1=mobile verified,0=mobile unverified');
          $table->tinyInteger('is_active')->default('0')->comment('0=inactive,1=active');
          $table->tinyInteger('is_delete')->default('0')->comment('1=delete');
          $table->tinyInteger('is_password_reset')->default('0')->comment('1=reset password');
          $table->tinyInteger('is_guest')->default('0')->comment('0=not guest,1=guest');
          $table->tinyInteger('is_notification')->default('1')->comment('1=on,0=off');
          $table->tinyInteger('is_email_news_later')->default('1')->comment('1=on,0=off');
          $table->string('ipaddress')->nullable();
          $table->dateTime('last_login')->nullable();
          $table->string('is_language')->default('en')->comment('en','ar');
          $table->string('my_referal_code')->nullable();
          $table->string('used_referal_code')->nullable();
          $table->double('amount', 10, 3)->default('0.000');
          $table->string('person_incharge')->nullable();
          $table->string('bank_name')->nullable();
          $table->string('iban')->nullable();
          $table->decimal('monthly_fees',8,2)->default('0.00')->comment('this key is percentage based');
          $table->bigInteger('created_by')->nullable();
          $table->enum('is_technician',['0','1'])->default(0)->comment('1 for technician adn 0 for as service provider');
          $table->rememberToken();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

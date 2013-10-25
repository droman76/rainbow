<?php
if ($facebook_loginbox)
    include('facebookloginconfirm.php');
  
?>
<noscript>
<H1>YOU MUST HAVE JAVASCRIPT ENABLED TO USE THIS SITE</H1>

<style>
  #pagebody { display:none; }
</style>

</noscript>

<div id='captcha' class='modal-bg' style='display:none'>

   <div style='left:35%;top:200px;width:250px;padding:40px;height:150px' class="modal">
    <?=$babel->say('p_typeincaptcha')?>:
    <input type='text' style='width:200px' id='captchatxt'>
    <img src="/page/userlogin/captcha/captcha.php" id="captcha" /><br/>
   
    <button onclick='signup_cont()' class="rainbow-button rainbow-button-submit"><?=$babel->say('p_okregister')?></button>
   </div>
</div>


<div id='requestpassword' style='margin:15px;display:none'>
   <h1><?=$babel->say('p_password_request_title')?></h1>
   <br>
   <?=$babel->say('p_password_request')?>   <br>
    <form id='psrequest_form'>
      <input type='text' id='passwordrequest' value=''><br><br>
      <button class='rainbow-button rainbow-button-submit' onclick='request_password()' name='request'><?=$babel->say('p_password_request_button')?></button>
    </form>
</div>



<div id='pagebody' class='welcome' >

  <div class='welcomeimage' style='float:left;'> 
    
  </div>
  <div class='registration_form'>
      <h1><?=$babel->say('registration_welcome')?></h1><br>

      <span style='font-size:14px'><?=$babel->say('p_registrationintro')?></span><br><br>
      <h2><?=$babel->say('registration_title')?></h2>
      <span style='font-size:14px'><?=$babel->say('p_registrationintro2')?></span><br>
      <a href='<?=$dialog_url?>'><span style='font-size:14px'><?=$babel->say('p_registrationintro3')?></span></a><br>
      
      
      <span style="display:inline-block;width:200px;">
        <form name="signupform" id="signupform" onsubmit="return false;">
          <div>  <?=$babel->say('registration_fullname')?>    :</div>
          <input id="name" type="text" onblur="checkusername()" onfocus="emptyElement('status')" maxlength="88"> 
          <span id="unamestatus"></span>
          <div>  <?=$babel->say('registration_gender')?>    :</div>
          <select style='border-color:lightgrey;font-size:14px;color:#474747' id="gender">
            <option value='f'><?=$babel->say('p_female',false)?></option>
            <option value='m'><?=$babel->say('p_male',false)?></option>
          </select>
          
          <div>  <?=$babel->say('registration_email')?>    :</div>
          <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
          <div>  <?=$babel->say('registration_password')?>    </div>
          <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
          <div></div>

          <br /><br />
          <button id="signupbtn" onclick="signup()">  <?=$babel->say('registration_button')?>    </button>
          <span id="status"></span>
          </form>
       
        </span>
        <span class='treeimage' style="position:relative;top:-40px;left:30px">
        
        </span>
      </span>
  </div>


</div>


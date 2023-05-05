1 вариант
/public_html/data/client.init.php

# ... Почта
  define("cms_system_mail","noreply@mailer.r52.ru");
  define("SMTP_HOST", "cpanel13.d.fozzy.com");
  define("SMTP_PORT", "25");
  define("SMTP_USER", "noreply@mailer.r52.ru");
  define("SMTP_PASS", "CniRTibVy#WOtbJe");
  define("TO_MAIL",""); # почта по умолчанию (зло :))

2 вариант
/public_html/config.php

# ... почтовые адреса
  if(DEBUG == false){
/ some code/
        //отсылать с адреса:
    $smtp_host = "cpanel13.d.fozzy.com";
    $smtp_port = "25";
    $smtp_user = "noreply@mailer.r52.ru";
    $smtp_pass = "CniRTibVy#WOtbJe";
  }else{
/ some code/
        //отсылать с адреса:
    $smtp_host = "cpanel13.d.fozzy.com";
    $smtp_port = "25";
    $smtp_user = "noreply@mailer.r52.ru";
    $smtp_pass = "CniRTibVy#WOtbJe";
  }


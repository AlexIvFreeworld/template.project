<?
/* aspro_allcorp3medc_order_page */
$MESS ["ORDER_PAGE_FORM_NAME"] = "Checkout";
$MESS ["ORDER_PAGE_BUTTON_NAME"] = "Send";
$MESS ["ORDER_PAGE_FORM_DESCRIPTION"] = "Fill out a simple form and our managers will contact you, answer any questions and prepare an invoice for payment";
$MESS ["ORDER_PAGE_FORM_QUESTION_1"] = "Your name";
$MESS ["ORDER_PAGE_FORM_QUESTION_2"] = "Phone";
$MESS["ORDER_PAGE_FORM_QUESTION_3"] = "E-mail";
$MESS["ORDER_PAGE_FORM_QUESTION_4"] = "Company name";
$MESS["ORDER_PAGE_FORM_QUESTION_5"] = "Delivery address";
$MESS["ORDER_PAGE_FORM_QUESTION_6"] = "Order comment";
$MESS["ORDER_PAGE_FORM_QUESTION_7"] = "Order Contents";
$MESS["EVENT_NEW_ORDER_PAGE_NAME"] = "New order from the site";
$MESS["EVENT_NEW_ORDER_PAGE_DESCRIPTION"] = "#NAME# - Visitor's name \ n
#PHONE# - Phone
#EMAIL# - Email
#COMPANY# - Company name
#ADDRESS# - Delivery address
#MESSAGE# - Comment to the order
#ORDER_LIST# - Order contents ";
$MESS["NEW_ORDER_PAGE_EMAIL_SUBJECT"] = "New order from the site";
$MESS["NEW_ORDER_PAGE_EMAIL_TEXT"] = "Completed the form \"Checkout\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor's name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Company name: #COMPANY#<br />
Delivery address: #ADDRESS#<br />
Order comment: #MESSAGE#<br />
Contents of the order: #ORDER_LIST#<br /> <br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_director */
$MESS["DIRECTOR_FORM_NAME"] = "Write to the head physician";
$MESS["DIRECTOR_BUTTON_NAME"] = "Send";
$MESS["DIRECTOR_FORM_DESCRIPTION"] = "If you have any questions about the work of our employees or the company in general, or if you want to say thanks, write a message and it will come to the director personally";
$MESS["DIRECTOR_FORM_QUESTION_1"] = "Your name";
$MESS["DIRECTOR_FORM_QUESTION_2"] = "Subject";
$MESS["DIRECTOR_FORM_QUESTION_3"] = "Message";
$MESS["EVENT_NEW_DIRECTOR_DESCRIPTION"] = "#NAME# - Visitor's name \ n
#TITLE# - Topic
#MESSAGE# - Message ";
$MESS["NEW_DIRECTOR_EMAIL_SUBJECT"] = "New letter to the head physician from the site";
$MESS["NEW_DIRECTOR_EMAIL_TEXT"] = "Completed form \"Write to the head physician\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitors name: #NAME#<br />
Subject: #TITLE#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_order_product */
$MESS["ORDER_PRODUCT_FORM_NAME"] = "Order a product";
$MESS["ORDER_PRODUCT_BUTTON_NAME"] = "Send";
$MESS["ORDER_PRODUCT_FORM_QUESTION_1"] = "Your name";
$MESS["ORDER_PRODUCT_FORM_QUESTION_2"] = "Phone";
$MESS["ORDER_PRODUCT_FORM_QUESTION_3"] = "E-mail";
$MESS["ORDER_PRODUCT_FORM_QUESTION_4"] = "Product";
$MESS["ORDER_PRODUCT_FORM_QUESTION_5"] = "Message";
$MESS["EVENT_NEW_ORDER_PRODUCT_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#PRODUCT# - Product
#MESSAGE# - Message ";
$MESS["NEW_ORDER_PRODUCT_EMAIL_SUBJECT"] = "New product order from the site";
$MESS["NEW_ORDER_PRODUCT_EMAIL_TEXT"] = "Completed the form \"Order a product\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Product: #PRODUCT#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";
    
/* aspro_allcorp3medc_quick_buy */
$MESS["QUICK_BUY_FORM_NAME"] = "Buy in 1 click";
$MESS["QUICK_BUY_BUTTON_NAME"] = "Send";
$MESS["QUICK_BUY_FORM_QUESTION_1"] = "Your name";
$MESS["QUICK_BUY_FORM_QUESTION_2"] = "Phone";
$MESS["QUICK_BUY_FORM_QUESTION_3"] = "E-mail";
$MESS["QUICK_BUY_FORM_QUESTION_4"] = "Product";
$MESS["QUICK_BUY_FORM_QUESTION_5"] = "Message";
$MESS["QUICK_BUY_FORM_QUESTION_6"] = "Product price";
$MESS["EVENT_QUICK_BUY_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#PRODUCT_NAME# - Product
#PRODUCT_PRICE# - Product price
#MESSAGE# - Message ";
$MESS["QUICK_BUY_EMAIL_SUBJECT"] = "New quick product order from the site";
$MESS["QUICK_BUY_EMAIL_TEXT"] = "Completed form \" Buy in 1 click \"on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Product: #PRODUCT_NAME#<br />
Product Price: #PRODUCT_PRICE#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_order_project */
/*
$MESS["ORDER_PROJECT_FORM_NAME"] = "Order Project";
$MESS["ORDER_PROJECT_BUTTON_NAME"] = "Send";
$MESS["ORDER_PROJECT_FORM_QUESTION_1"] = "Your name";
$MESS["ORDER_PROJECT_FORM_QUESTION_2"] = "Phone";
$MESS["ORDER_PROJECT_FORM_QUESTION_3"] = "E-mail";
$MESS["ORDER_PROJECT_FORM_QUESTION_4"] = "Project";
$MESS["ORDER_PROJECT_FORM_QUESTION_5"] = "Message";
$MESS["EVENT_NEW_ORDER_PROJECT_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#PROJECT# - Project
#MESSAGE# - Message ";
$MESS["NEW_ORDER_PROJECT_EMAIL_SUBJECT"] = "New project order from the site";
$MESS["NEW_ORDER_PROJECT_EMAIL_TEXT"] = "Completed the form \"Order a project\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Project: #PROJECT#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";
*/

/* aspro_allcorp3medc_order_services */
$MESS["ORDER_SERVICES_FORM_NAME"] = "Order a service";
$MESS["ORDER_SERVICES_BUTTON_NAME"] = "Send";
$MESS["ORDER_SERVICES_FORM_QUESTION_1"] = "Your name";
$MESS["ORDER_SERVICES_FORM_QUESTION_2"] = "Phone";
$MESS["ORDER_SERVICES_FORM_QUESTION_3"] = "E-mail";
$MESS["ORDER_SERVICES_FORM_QUESTION_4"] = "Service";
$MESS["ORDER_SERVICES_FORM_QUESTION_5"] = "Message";
$MESS["EVENT_NEW_ORDER_SERVICES_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#SERVICE# - Service
#MESSAGE# - Message ";
$MESS["NEW_ORDER_SERVICES_EMAIL_SUBJECT"] = "New service order from the site";
$MESS["NEW_ORDER_SERVICES_EMAIL_TEXT"] = "Completed form \"Order service\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Service: #SERVICE#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_order_study */
$MESS["ORDER_STUDY_FORM_NAME"] = "Order a course";
$MESS["ORDER_STUDY_BUTTON_NAME"] = "Send";
$MESS["ORDER_STUDY_FORM_QUESTION_1"] = "Your name";
$MESS["ORDER_STUDY_FORM_QUESTION_2"] = "Phone";
$MESS["ORDER_STUDY_FORM_QUESTION_3"] = "E-mail";
$MESS["ORDER_STUDY_FORM_QUESTION_4"] = "Course";
$MESS["ORDER_STUDY_FORM_QUESTION_5"] = "Message";
$MESS["EVENT_NEW_ORDER_STUDY_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#STUDY# - Course
#MESSAGE# - Message ";
$MESS["NEW_ORDER_STUDY_EMAIL_SUBJECT"] = "New service order from the site";
$MESS["NEW_ORDER_STUDY_EMAIL_TEXT"] = "Completed the form \"Order a course\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Course: #STUDY#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_question */
$MESS["QUESTION_FORM_NAME"] = "Ask a question";
$MESS["QUESTION_BUTTON_NAME"] = "Send";
$MESS["QUESTION_FORM_DESCRIPTION"] = "Our administrators will answer any question about the service";
$MESS["QUESTION_FORM_QUESTION_1"] = "Your name";
$MESS["QUESTION_FORM_QUESTION_2"] = "Phone";
$MESS["QUESTION_FORM_QUESTION_3"] = "E-mail";
$MESS["QUESTION_FORM_QUESTION_4"] = "The product / service of interest";
$MESS["QUESTION_FORM_QUESTION_5"] = "Message";
$MESS["EVENT_NEW_QUESTION_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#NEED_PRODUCT# - The product / service of interest
#MESSAGE# - Message ";
$MESS["NEW_QUESTION_EMAIL_SUBJECT"] = "New question from the site";
$MESS["NEW_QUESTION_EMAIL_TEXT"] = "Completed the form \"Ask a question\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
The product / service of interest: #NEED_PRODUCT#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_callstaff */
$MESS["CALLSTAFF_FORM_NAME"] = "Contact Employee";
$MESS["CALLSTAFF_BUTTON_NAME"] = "Send";
$MESS["CALLSTAFF_FORM_DESCRIPTION"] = "";
$MESS["CALLSTAFF_FORM_QUESTION_1"] = "Your name";
$MESS["CALLSTAFF_FORM_QUESTION_2"] = "Phone";
$MESS["CALLSTAFF_FORM_QUESTION_3"] = "E-mail";
$MESS["CALLSTAFF_FORM_QUESTION_4"] = "Employee";
$MESS["CALLSTAFF_FORM_QUESTION_5"] = "Employee Email";
$MESS["CALLSTAFF_FORM_QUESTION_6"] = "Message";
$MESS["EVENT_NEW_CALLSTAFF_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#STAFF# - Employee
#STAFF_EMAIL_HIDDEN# - Email of the employee
#MESSAGE# - Message ";
$MESS["NEW_CALLSTAFF_EMAIL_SUBJECT"] = "New message to the employee from the site";
$MESS["NEW_CALLSTAFF_EMAIL_TEXT"] = "Completed form \"Write to employee\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Employee: #STAFF#<br />
Employee Email: #STAFF_EMAIL_HIDDEN#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_feedback */
$MESS["FEEDBACK_FORM_NAME"] = "Give feedback";
$MESS["FEEDBACK_BUTTON_NAME"] = "Send";
$MESS["FEEDBACK_FORM_DESCRIPTION"] = "";
$MESS["FEEDBACK_FORM_QUESTION_1"] = "Your name";
$MESS["FEEDBACK_FORM_QUESTION_2"] = "Phone";
$MESS["FEEDBACK_FORM_QUESTION_3"] = "E-mail";
$MESS["FEEDBACK_FORM_QUESTION_4"] = "Title";
$MESS["FEEDBACK_FORM_QUESTION_5"] = "File";
$MESS["FEEDBACK_FORM_QUESTION_6"] = "Message";
$MESS["FEEDBACK_FORM_QUESTION_RATING"] = "Your rating";
$MESS["FEEDBACK_FORM_QUESTION_PHOTO"] = "Your photo";
$MESS["FEEDBACK_FORM_QUESTION_SPECIALIST"] = "Employee";
$MESS["EVENT_NEW_FEEDBACK_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#POST# - Position
#PHOTO# - Your photo
#FILE# - Files
#RATING# - Your rating
#SPECIALIST# - Employee
#MESSAGE# - Message ";
$MESS["NEW_FEEDBACK_EMAIL_SUBJECT"] = "New feedback from the site";
$MESS["NEW_FEEDBACK_EMAIL_TEXT"] = "Completed form \"Leave feedback \" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Position: #POST#<br />
Photo: #PHOTO#<br />
Files: #FILES#<br />
Employee: #SPECIALIST#<br />
Rating: #RATING#<br />
Message: #MESSAGE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_order_resume */
$MESS["RESUME_FORM_NAME"] = "Submit Resume";
$MESS["RESUME_BUTTON_NAME"] = "Send";
$MESS["RESUME_FORM_QUESTION_1"] = "Your name";
$MESS["RESUME_FORM_QUESTION_2"] = "Phone";
$MESS["RESUME_FORM_QUESTION_3"] = "E-mail";
$MESS["RESUME_FORM_QUESTION_4"] = "Desired Position";
$MESS["RESUME_FORM_QUESTION_5"] = "Additional Information";
$MESS["RESUME_FORM_QUESTION_6"] = "Resume file";
$MESS["EVENT_NEW_QUESTION_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#EMAIL# - E-mail
#POST# - Desired post
#MESSAGE# - Additional information
#FILE# - File with resume ";
$MESS["NEW_RESUME_EMAIL_SUBJECT"] = "New resume from the site";
$MESS["NEW_RESUME_EMAIL_TEXT"] = "Completed form \"Send resume\" on the site #SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Email: #EMAIL#<br />
Desired position: #POST#<br />
More information: #MESSAGE#<br />
Resume file: #FILE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_order_callback */
$MESS["CALLBACK_FORM_NAME"] = "Request a call";
$MESS["CALLBACK_BUTTON_NAME"] = "Send";
$MESS["CALLBACK_FORM_DESCRIPTION"] = "Introduce yourself, we will call you back.";
$MESS["CALLBACK_FORM_QUESTION_1"] = "Your name";
$MESS["CALLBACK_FORM_QUESTION_2"] = "Phone";
$MESS["EVENT_NEW_QUESTION_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone ";
$MESS["NEW_CALLBACK_EMAIL_SUBJECT"] = "New call from the site";
$MESS["NEW_CALLBACK_EMAIL_TEXT"] = "Completed the form \"Request a call\" on the site #SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_online */
$MESS["ONLINE_FORM_NAME"] = "Online appointment";
$MESS["ONLINE_BUTTON_NAME"] = "Send";
$MESS["ONLINE_FORM_DESCRIPTION"] = "The clinic administrator will be happy to answer your questions and make an appointment with the specialist you need.";
$MESS["ONLINE_FORM_QUESTION_1"] = "Your name";
$MESS["ONLINE_FORM_QUESTION_2"] = "Phone";
$MESS["ONLINE_FORM_QUESTION_3"] = "Convenient date and time";
$MESS["ONLINE_FORM_QUESTION_4"] = "Choose a specialization";
$MESS["ONLINE_FORM_QUESTION_5"] = "Specialist";
$MESS["ONLINE_FORM_QUESTION_6"] = "The product / service of interest";
$MESS["EVENT_NEW_ONLINE_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#SPECIALIZATION# - Choosed specialization
#SPECIALIST# - Specialist
#DATE# - Convenient date and time
#PRODUCT# - The product / service of interest";
$MESS["NEW_ONLINE_EMAIL_SUBJECT"] = "New appointment from the site";
$MESS["NEW_ONLINE_EMAIL_TEXT"] = "Completed the form \"Online appointment\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Choosed specialization: #SPECIALIZATION#<br />
Specialist: #SPECIALIST#<br />
Convenient date and time: #DATE#<br />
The product / service of interest: #PRODUCT#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";

/* aspro_allcorp3medc_online_short */
$MESS["ONLINE_SHORT_FORM_NAME"] = "Online appointment";
$MESS["ONLINE_SHORT_BUTTON_NAME"] = "Send";
$MESS["ONLINE_SHORT_FORM_DESCRIPTION"] = "The clinic administrator will be happy to answer your questions and make an appointment with the specialist you need.";
$MESS["ONLINE_SHORT_FORM_QUESTION_1"] = "Your name";
$MESS["ONLINE_SHORT_FORM_QUESTION_2"] = "Phone";
$MESS["ONLINE_SHORT_FORM_QUESTION_3"] = "Message";
$MESS["ONLINE_SHORT_FORM_QUESTION_4"] = "The product / service of interest";
$MESS["EVENT_NEW_ONLINE_SHORT_DESCRIPTION"] = "#NAME# - Visitor name \ n
#PHONE# - Phone
#MESSAGE# - Message
#PRODUCT# - The product / service of interest";
$MESS["NEW_ONLINE_SHORT_EMAIL_SUBJECT"] = "New appointment from the site";
$MESS["NEW_ONLINE_SHORT_EMAIL_TEXT"] = "Completed the form \"Online appointment\" on the site#SITE_NAME#(#RS_RESULT_ID#) <br />
Visitor Name: #NAME#<br />
Phone: #PHONE#<br />
Message: #MESSAGE#<br />
The product / service of interest: #PRODUCT#<br />

Request sent: #RS_DATE_CREATE#
View the result on the site: <a href='http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#>http://#SERVER_NAME#/bitrix/admin/form_result_edit.php?lang=en&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#&WEB_FORM_NAME=#RS_FORM_NAME#</a>";
?>
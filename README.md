# Attendance-Web-Application
This is the thesis of Group H 2022-2023's <b>Development of an Attendance Monitoring System with a Portable RFID-Based Logging Device</b>, in fulfillment for the bachelor's degree in computer engineering at University of San Carlos.</br></br>
The web application should be used in tandem with the attendance logging system (also known as Portable Logging Device) developed with Raspberry Pi Zero W. The web server used is XAMPP. Users can access the web application on any computer if the computer has XAMPP installed, and is in LAN.

Alpha testing cases for the web application and the portable device can be found <a href = "https://docs.google.com/spreadsheets/d/1Um_i__vagtg8pD9HuRssXADdYC0kXuq-DAEUPDDeT34/edit?usp=sharing">here</a> (only for USC email access).

# <hr> <b>Helpful Tutorials </b></hr>
<br/>
<ol>
  <li> <a href = "https://www.freecodecamp.org/news/git-and-github-for-beginners/">How to use Git and Github</a> </li>
  <li> <a href = "https://git-scm.com/downloads" > Initial Git Setup </a> </li>
  <li> <a href = "https://stackoverflow.com/questions/18667582/run-my-php-files-from-outside-htdocs" > Change localhost access from htdocs to any folder </a> </li>
  <li> <a href = "https://stackoverflow.com/questions/5524116/accessing-localhost-xampp-from-another-computer-over-lan-network-how-to">Access webpage in any computer</a> </li>
  <li> Configure XAMPP for sending emails <a href = "https://www.geeksforgeeks.org/how-to-configure-xampp-to-send-mail-from-localhost-using-php/">[1]</a> <a href = "https://www.w3docs.com/snippets/php/how-to-configure-xampp-to-send-email-from-localhost-with-php.html">[2]</a> <a href = "https://phpflow.com/php/how-to-send-email-from-localhost-using-php/">[3]</a></li>
  <li> <a href = "https://myaccount.google.com/lesssecureapps">Turn on Google's "Less Secure Apps" setting for XAMPP sending emails</a> </li>
</ol>
<br/>
<hr/>
For the XAMPP sendmail.ini and php.ini settings, these are the current working settings as of 3 Aug 2023:
<br/><br/>
<b>For sendmail.ini</b>
<br/> <br/>
1.) smtp_server should be <i><b>smtp@gmail.com.</b></i> smtp_port should be <i><b>587</b></i>. </b>smtp_ssl should be <i><b>tls.</b></i> 
<br/>

![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/7c24eee0-30a8-49e9-98bc-3297569b4404)

<br/>
2.) Uncomment <i><b>error_logfile</b></i> and <i><b>debug_logfile</b> </i>to check if there are any errors during the sending of emails.
<br/>

![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/6650ff56-3655-4394-b5fe-3fede350cc24)

<br/>
3.) Set the email and password to where you want to use as the sender.
<br/> 

![sendmail](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/194b2111-5c69-47ac-8d65-a93b83eb249a)

<br/>
4.) Set force_sender to the same email where you want to use as sender.
<br/>

![emaiul](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/446fea27-8175-4e51-b67d-58fc849469f6)

<br/>
5.) Set hostname to <i><b>localhost.</b></i>
<br/> 

![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/7bd5ff0b-a90f-4cf8-9900-ee9a87a9a6b2/)

<br/>

<hr/>
<b>For php.ini</b>
<br/><br/>
1.) The <i><b>php_openssl.dll</b></i> extension must be uncommented.
<br/>

![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/ea9f94ab-97d2-43f2-ab1f-b235960b94b3)

<br/>
2.) SMTP should be <i><b>SMTP.gmail.com</b></i>. smtp_port should be <i><b>587</b></i>. sendmail_from should be <i><b>where you can configure Google's "Less Secure Apps" option.</b></i> sendmail_path should be <i><b>where the sendmail.exe file is located on your PC, WITH THE ARGUMENT.</b></i>
<br/>

![smtp](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/5304c5f0-f119-453d-baf1-dfde9ec51cb9)

<br/>

# <hr><b>Required Installations</b></hr>
<ol>
  <li><a href = "https://www.apachefriends.org/download.html">XAMPP</a> - This is for putting the web application on localhost. </li>
  <li><a href = "https://getcomposer.org/download/">Composer</a> - Install this on your computer for getting PhpSpreadSheet.</li>
  <li><a href = "https://github.com/PHPOffice/PhpSpreadsheet">PhpSpreadSheet</a> - Install the library to <i>the working folder of the web application.</i> The system will not work if the library isn't in the specified folder. <i>Note: During installation, there might be errors such as "missing ext-gd" and "missing-zip". Uncomment these in the php.ini file and run the installation again.</i></li>
  <br/>
  
You can uncomment the <i><b>gd extension</b></i> in the "Dynamic Extensions" section found in php.ini file.
![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/88db606d-6787-4def-80c1-952da5cbdeca)

  
  <li><a href = "https://github.com/canton7/SyncTrayzor/tree/v1.1.29" >SyncTrayzor</a> - This is for syncing from the local server to the Raspberry Pi and vice versa. <i>Please install Syncthing on the Raspberry Pi also.</i></li>
 </ol>

<br/>

# <hr><b>Setting Up The Web Application</b></hr>
<br/>
1.) Launch XAMPP Control Panel and press the <b>Start</b> buttons beside <b>Apache and MySQL.</b> The Apache and MySQL titles will have a green background if everything is successful.
<br/>

![xamppSettings](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/7afb61d6-6650-4281-8a5a-860dc478f5f7)

2.) To register an administrator in the web application, open your preferred web browser and navigate to <i><b>http\://localhost/Proponents Use Only/register-admin.php/</b></i>. Register the username and password for the administrator and press the Register button. If you want to change an already registered administrator's password, you may do so by clicking the <b>Change Administrator Password</b> button.
<br/>

![registerAdmin](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/c26c09fc-0ac5-40c5-9ad0-5a6193f3b4a2)

<b><i>Note: You can access the database used for storing all data by the web application by going through phpMyAdmin <u>(http\://localhost/phpmyadmin/)</u>.</i></b>

3.) To open the starting page of the web application, navigate to <i><b>http\://localhost/teacher-login.php/</b></i> in the address bar. <b>Do not use https://</b> as it might be detected by the website as unsafe. <u>It is safe.</u>
<br/>
![homepage](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/2d634ced-f379-4847-a748-7e7a14cd967e)





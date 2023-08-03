# Attendance-Web-Application
This is the thesis of Group H 2022-2023's <b>Development of an Attendance Monitoring System with a Portable RFID-Based Logging Device</b>, in fulfillment for the bachelor's degree in computer engineering at University of San Carlos.</br></br>
The web application should be used in tandem with the attendance logging system (also known as Portable Logging Device) developed with Raspberry Pi Zero W. The web server used is XAMPP. Users can access the web application on any computer if the computer has XAMPP installed, and is in LAN.

Alpha testing cases for the web application and the portable device can be found <a href = "https://docs.google.com/spreadsheets/d/1Um_i__vagtg8pD9HuRssXADdYC0kXuq-DAEUPDDeT34/edit?usp=sharing">here</a> (only for USC email access).

<hr> <b>Helpful Tutorials </b></hr>
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
<br/>
<b>For sendmail.ini</b>
<br/> <br/>
1.) smtp_server should be <b>smtp@gmail.com.</b> smtp_ssl should be <b>tls.</b> 
<br/>
![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/7c24eee0-30a8-49e9-98bc-3297569b4404)
<br/>
2.) Uncomment <b>error_logfile</b> and <b>debug_logfile</b> for you to check if there are errors during sending of mails.
<br/>
![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/6650ff56-3655-4394-b5fe-3fede350cc24)
<br/>
3.) Set your email and password to where you want to use as sender.
<br/> ![sendmail](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/194b2111-5c69-47ac-8d65-a93b83eb249a)
<br/>
4.) Set force_sender to the same email where you want to use as sender.
<br/>
![emaiul](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/3e3114c0-83df-4911-be7d-86b651281f90)
<br/>
5.) Set hostname to <b>localhost.</b>
<br/> 
![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/7bd5ff0b-a90f-4cf8-9900-ee9a87a9a6b2)
<br/>

<hr/>
<b>For php.ini</b>
<br/><br/>
1.) The <b>php_openssl.dll</b> extension must be uncommented.
<br/>
![image](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/ea9f94ab-97d2-43f2-ab1f-b235960b94b3)
<br/>
2.) SMTP should be <b>SMTP.gmail.com</b>. smtp_port should be <b>587</b>. sendmail_from should be <b>where you can configure Google's "Less Secure Apps" option.</b> sendmail_path should be <b>where the sendmail.exe file is located on your PC, WITH THE ARGUMENT.</b>
<br/>
![smtp](https://github.com/grusnivis/Attendance-Web-Application/assets/59056214/5304c5f0-f119-453d-baf1-dfde9ec51cb9)
<br/>

<hr/>
<b>Required Installations</b>
<ol>
  <li><a href = "https://www.apachefriends.org/download.html">XAMPP</a> - This is for putting the web application on localhost. </li>
  <li><a href = "https://getcomposer.org/download/">Composer</a> - Install this on your computer for getting PhpSpreadSheet.</li>
  <li><a href = "https://github.com/PHPOffice/PhpSpreadsheet">PhpSpreadSheet</a> - Install the library to <i>the working folder of the web application.</i> The system will not work if the library isn't in the specified folder.</li>
  <li><a href = "https://github.com/canton7/SyncTrayzor/tree/v1.1.29" >SyncTrayzor</a> - This is for syncing from the local server to the Raspberry Pi and vice versa. <i>Please install Syncthing on the Raspberry Pi also.</i></li>
 </ol>
  

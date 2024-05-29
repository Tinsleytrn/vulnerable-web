# vulnerable-web
- Trang web PHP với lỗ hổng: 
1.SQLi injection Sleep (Done)
2.File Upload (Done)
3.Insecure Derialization (Done)
Picked: IDOR , Access Control, XSS

Web Structure:
1/ Sign in, Sign Up Form (Done)
-> Insecure Deserialization
* Cookie
-> SQL Injection time based(Done):
+ Payload SQL Injection: ' OR '1'='1 into Password inpt, username canbe anything
+ Payload Timebased: using burpsuite to attack:
{base}' and (select*from(select(sleep(20)))a)-- 

2/ Home Page: Shopping Table (Done)
* Upload function (upload.php)
-> XSS payload (Done): <script>alert('Hi, my name is Hacker')</script> 
<script>document.body.innerHTML='<h1>Hacked!</h1>'</script>
+

3/ User Page:
* Upload img, change bio
-> File Upload: Web shell upload via Content-Type restriction bypass (Done)
+ Payload: Shell.php 
- Level 1: With vulnerable code, can upload easy
- Level 2: With more secure code, just use burp suite and race condition file upload, can fix with lock file code



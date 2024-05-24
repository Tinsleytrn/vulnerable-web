- Trang web PHP với lỗ hổng: 
SQLi injection Sleep (Done)
File Upload (Done)
Insecure Derialization (Done)

Picked: IDOR , Access Control

Web Structure:
1/ Sign in, Sign Up Form (Done)
-> Insecure Deserialization
* Cookie
-> SQL Injection time based(Done):
+ Payload SQL Injection: ' OR '1'='1 into Password inpt, username canbe anything
+ Payload Timebased: using burpsuite to attack:
{base}' and (select*from(select(sleep(20)))a)-- 

2/ Home Page: Dashboard User
* Search function
-> IDOR

3/ User Page:
* Upload img, change bio
-> File Upload: Web shell upload via Content-Type restriction bypass (Done)
+ Payload: Shell.php 
- Level 1: With vulnerable code, can upload easy
- Level 2: With more secure code, just use burp suite and race condition file upload, can fix with lock file code


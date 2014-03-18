Revue Booking System
====================

Instructions for setting up your own development server:
1. Install apache, php and mysql.
2. Ensure php is configured to allow short_open_tags.
3. Clone the repo and put it in a place where apache can see it. Verify that
   this worked by trying to open your local version of the site in a web
   browser. You should get an "unable to connect to database" message.
4. Set the values in include/settings.php as per your mysql configuration (see
   the CSE Revue Webmin Head for passwords to the production server's database).
5. If you want data from past years to test your site, see the CSE Revue Webmin
   Head about that too.

1. get source code and sql file from https://github.com/araneta/drupal9
2. create new database for drupal9 then import the sql file dump-drupal9-202211222157.sql
3. create a virtual host for drupal9. create file  /etc/apache2/sites-enabled/drupal9.conf  and the content should be like this (you can modify the folder)
<VirtualHost *:80>
        ServerName drupal9.local
        ServerAlias www.drupal9.local
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/drupal9/voxteneo/web
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
       <Directory /var/www/drupal9/voxteneo/web>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
4. restart apache2
5. update the database configuration on /web/sites/default/settings.php
6. login to admin page on http://drupal9.local/user/login with username: admin password:newpasswd                                                                                                



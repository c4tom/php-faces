    RewriteEngine on
    RewriteCond $1 !^(index\.php|images|themes|css|js|video_files|robots\.txt|favicon\.ico)
    
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^(.*)$|index.php$ index.php/$1 [L]
    
    Options +ExecCGI
    AddType application/x-httpd-php .phpf s
    AddHandler  application/x-httpd-phpf .phpf
    
    Action application/x-httpd-phpf www.yourhost.com/system/phpf.php

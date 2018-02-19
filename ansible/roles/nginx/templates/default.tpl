server {
    listen 80;
    listen 443 ssl;

    root {{ nginx.docroot }};
    index index.html index.php;

    server_name {{ nginx.servername }};

    ssl_certificate     /etc/nginx/ssl/devcert.crt;
    ssl_certificate_key /etc/nginx/ssl/devcert.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    error_page 404 /404.html;

    error_page 500 502 503 504 /50x.html;
        location = /50x.html {
        root /usr/share/nginx/www;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php5.6-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

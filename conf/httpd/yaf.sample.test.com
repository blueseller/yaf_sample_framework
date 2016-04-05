server {
    listen 80 ;
    server_name lyaf.sample.test.com; 
    
    index index.php;
    root /tmp/bmg_cms/;
    access_log  /tmp/logs/cms-access.log combinedio;
    error_log  /tmp/logs/cms-error.log;
    
    if ($request_uri ~ " ") {
            return 444;
    }
    
    location / {
    }

    location ~* ^/application{
        rewrite (.*) /index.php last;
    }
    location ~* ^/conf{
        rewrite (.*) /index.php last;
    }
    location = /favicon.ico {
        allow all;
        log_not_found off;
        access_log off;
    }

    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location ~ \.php$ {
        if ( $fastcgi_script_name ~ \..*\/.*php ) {
            return 403;
        }
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        include fastcgi.conf;
    }

}


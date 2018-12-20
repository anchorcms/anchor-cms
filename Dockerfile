FROM alpine:3.6

RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories

RUN apk update && apk upgrade && \
    apk add bash wget curl apache2

RUN apk add php7-mbstring php7-mcrypt \
    php7-apache2 php7-openssl php7-curl php7-json \
    php7-pdo php7-pdo_mysql php7-gd \
    php7-intl php7-opcache php7-session

RUN sed -i "s|display_errors = Off|display_errors = On|" /etc/php7/php.ini && \
    sed -i "s|variables_order = .*|variabes_order = EGPCS|" /etc/php7/php.ini && \
    sed -i "s|;cgi.fix_pathinfo=1|cgi.fix_pathinfo=0|" /etc/php7/php.ini && \
    sed -i "s|#LoadModule rewrite_module modules/mod_rewrite.so|LoadModule rewrite_module modules/mod_rewrite.so|" /etc/apache2/httpd.conf && \
    sed -i 's#^DocumentRoot ".*#DocumentRoot "/var/www/html"#g' /etc/apache2/httpd.conf && \
    echo '<Directory "/var/www/html/">' >> /etc/apache2/httpd.conf && \
    echo 'Require all granted' >> /etc/apache2/httpd.conf && \
    echo 'AllowOverride FileInfo' >> /etc/apache2/httpd.conf && \
    echo '</Directory>' >> /etc/apache2/httpd.conf && \
    echo 'HttpProtocolOptions "Unsafe"' >> /etc/apache2/httpd.conf && \
    mkdir /run/apache2/ && \
    ln -sf /dev/null /var/log/apache2/access.log && \
    ln -sf /dev/stderr /var/log/apache2/error.log

RUN rm -rf /var/cache/apk/*

WORKDIR /var/www/html
VOLUME ["/var/www/html"]

EXPOSE 80

ENTRYPOINT ["/usr/sbin/httpd"]
CMD ["-D", "FOREGROUND"]

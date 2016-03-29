
$script = <<SCRIPT
wget https://www.dotdeb.org/dotdeb.gpg
apt-key add dotdeb.gpg
echo '
deb http://packages.dotdeb.org jessie all
deb-src http://packages.dotdeb.org jessie all
' > /etc/apt/sources.list.d/dotdeb.list
apt-key adv --keyserver keyserver.ubuntu.com --recv-keys C300EE8C
echo '
deb http://nginx.org/packages/debian/ jessie nginx
deb-src http://nginx.org/packages/debian/ jessie nginx
' > /etc/apt/sources.list.d/nginx.list
apt-get -q update
apt-get -y -q upgrade
apt-get -y -q install dkms nginx php7.0-fpm php7.0-sqlite php7.0-mysqlnd php7.0-imagick php7.0-curl
echo '
server {
	listen 80 default_server;
	server_name _;
	root /var/www/html/web;
	index index.php index.html;
	location / {
		try_files $uri $uri/ /index.php?$args;
	}
	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass 127.0.0.1:9000;
	}
}
' > /etc/nginx/sites-available/default
service nginx restart
echo '
[www]
user = www-data
group = www-data
listen = 127.0.0.1:9000
listen.owner = www-data
listen.group = www-data
pm = static
pm.max_children = 5
pm.max_requests = 500
' > /etc/php/7.0/fpm/pool.d/www.conf
service php7.0-fpm restart
SCRIPT

Vagrant.configure("2") do |config|
	config.vm.box = "debian/jessie64"
	config.vm.provision "shell", run: "once", inline: $script
	config.vm.network :forwarded_port, guest: 80, host: 8080
	config.vm.synced_folder ".", "/var/www/html", owner: "www-data", group: "www-data"
end

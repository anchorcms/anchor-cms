
$script = <<SCRIPT
apt-get -q update
apt-get -y -q upgrade
apt-get -y -q install nginx php5-fpm php5-sqlite php5-mysqlnd php5-imagick php5-curl
echo '
server {
	listen 80 default_server;
	server_name _;
	root /var/www/html;
	index index.php index.html;
	location / {
		try_files $uri $uri/ /index.php;
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
' > /etc/php5/fpm/pool.d/www.conf
service php5-fpm restart
SCRIPT

Vagrant.configure("2") do |config|
	config.vm.box = "debian/jessie64"
	config.vm.provision "shell", run: "once", inline: $script
	config.vm.network :forwarded_port, guest: 80, host: 8080
	config.vm.synced_folder ".", "/var/www/html", owner: "www-data", group: "www-data", type: "rsync"
end

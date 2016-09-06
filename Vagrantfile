VAGRANTFILE_API_VERSION = "2"

Vagrant.require_version ">=  1.8.4"

# Install missing pluggins
required_plugins = %w( vagrant-hostmanager vagrant-vbguest )
plugins_to_install = required_plugins.select { |plugin| not Vagrant.has_plugin? plugin }
if not plugins_to_install.empty?
    puts "Installing plugins: #{plugins_to_install.join(' ')}"
    if system "vagrant plugin install #{plugins_to_install.join(' ')}"
        exec "vagrant #{ARGV.join(' ')}"
    else
        abort "Installation of one or more plugins has failed. Aborting."
    end
end



Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    config.vm.hostname = "pon.dev"
    config.vm.network "private_network", ip: "192.168.56.103"

    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.include_offline = true
    config.hostmanager.ignore_private_ip = false
     config.hostmanager.aliases = %w(pon.dev)
  config.vm.define "default" do |default|
    default.vm.box = "minimum/centos-7-docker"
    default.vm.synced_folder ".", "/var/www", type: "nfs",:mount_options => ['nolock,vers=3,udp,noatime']


    default.ssh.insert_key = false
    default.ssh.shell = "bash -l"
    default.ssh.keep_alive = true
    default.vm.provision :shell, inline: 'echo 1 > /proc/sys/net/ipv4/ip_forward'
    default.vm.provision :shell, inline: 'echo "cd /var/www/application" >> /home/vagrant/.bashrc'
    default.vm.provision "shell", path: "vagrant/bin/clean.sh"
    default.vm.provision :shell, inline: 'mkdir -p /var/www/vagrant/data/elasticsearch /var/www/vagrant/data/mysql /var/www/vagrant/data/rabbitmq /var/www/application/node_modules /var/www/application/bower_components'
    default.vm.provision :shell, inline: 'sudo restorecon -Rv -n /var/www/vagrant/data/elasticsearch'
    default.vm.provision :shell, inline: 'sudo restorecon -Rv -n /var/www/vagrant/data/mysql'
    default.vm.provision :shell, inline: 'sudo restorecon -Rv -n /var/www/vagrant/data/rabbitmq'
    default.vm.provision :shell, inline: 'sudo restorecon -Rv -n /var/www/application/node_modules'
    default.vm.provision :shell, inline: 'sudo restorecon -Rv -n /var/www/application/bower_components'

    default.vm.provider "virtualbox" do |v|
       v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
       v.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
       v.customize ["modifyvm", :id, "--memory", 2048]
       v.customize ["setextradata", :id, "--VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
    end

    default.vm.provision "docker" do |app|
      app.run "debian",
            name: "app",
            cmd: "bash",
            args: "-ti -v /var/www/application:/var/www/pon:rw,z -v /var/www/vagrant/data/mysql:/data/mariadb:rw,z --user 1000:50 -w /var/www/vagrant/data/elasticsearch -w /var/www/vagrant/data/mysql -w /var/www/vagrant/data/rabbitmq"
    end


    default.vm.provision "docker" do |mysql|
      mysql.build_image "/var/www/vagrant/docker/mysql", args: "-t pon/mysql"
      mysql.run "pon/mysql",
            name: "mysql",
            cmd: "bash",
            args: "-ti -p 3306:3306 --volumes-from='app'"
    end

    default.vm.provision "docker" do |es|
      es.run "elasticsearch",
            name: "elasticsearch",
            cmd: "elasticsearch",
            args: "-ti -p 9200:9200 -v /var/www/vagrant/data/elasticsearch:/usr/share/elasticsearch/data:rw,z  --user 1000:50 "
    end

    default.vm.provision "docker" do |rabbitmq|
      rabbitmq.run "rabbitmq:3-management",
            name: "rabbitmq",
            cmd: "rabbitmq-server",
            args: "-ti -p 15672:15672 -p 5672:5672 -v /var/www/vagrant/data/rabbitmq:/var/lib/rabbitmq:rw,z  --user 1000:50 "
    end

    default.vm.provision "docker" do |phpfpm|
      phpfpm.build_image "/var/www/vagrant/docker/phpfpm", args: "-t pon/phpfpm"
      phpfpm.run "pon/phpfpm",
            name: "phpfpm",
            cmd: "bash",
            args: "-ti -p 9000:9000 -e SYMFONY__DATABASE__HOST='mysql' -e SYMFONY__ELASTICSEARCH__HOST='elasticsearch' -e SYMFONY__RABBITMQ__HOST='rabbitmq' --link mysql:mysql --link elasticsearch:elasticsearch --link rabbitmq:rabbitmq  --volumes-from='app'"
    end

    default.vm.provision "docker" do |nginx|
      nginx.build_image "/var/www/vagrant/docker/nginx", args: "-t pon/nginx"
      nginx.run "pon/nginx",
            name: "nginx",
            cmd: "bash",
            args: "-ti -p 80:80 --link mysql:mysql --link phpfpm:phpfpm -e DOMAIN=pon.dev,www.pon.dev -e ENVPON=app_dev --volumes-from='app'"
    end

    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/restart.sh /usr/bin/restart && chmod a+x /usr/bin/restart'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/clean.sh /usr/bin/clean && chmod a+x /usr/bin/clean'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/mysql.sh /usr/bin/mysql && chmod a+x /usr/bin/mysql'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/mysqldump.sh /usr/bin/mysqldump && chmod a+x /usr/bin/mysqldump'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/login.sh /usr/bin/login && chmod a+x /usr/bin/login'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/composer.sh /usr/bin/composer && chmod a+x /usr/bin/composer'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/console.sh /usr/bin/console && chmod a+x /usr/bin/console'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/npm.sh /usr/bin/npm && chmod a+x /usr/bin/npm'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/bower.sh /usr/bin/bower && chmod a+x /usr/bin/bower'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/gulp.sh /usr/bin/gulp && chmod a+x /usr/bin/gulp'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/cache.sh /usr/bin/cache && chmod a+x /usr/bin/cache'
    default.vm.provision :shell, inline: 'cp /var/www/vagrant/bin/queue.sh /usr/bin/queue && chmod a+x /usr/bin/queue'
    default.vm.provision :shell, inline: 'sudo restorecon -Rv -n /var/www/application'

  end


end
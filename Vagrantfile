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

    config.vm.box = "ubuntu/trusty64"
    config.vm.network "private_network", ip: "192.168.56.103"
    config.vm.provision :docker

    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.include_offline = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.aliases = %w(pon.dev)

    config.vm.define "default" do |default|
    default.vm.synced_folder ".", "/var/www", type: "nfs",:mount_options => ['nolock,vers=3,udp,noatime']

    default.ssh.insert_key = false
    default.ssh.shell = "bash -l"
    default.ssh.keep_alive = true
    default.vm.provision :shell, inline: 'echo "cd /var/www/application" >> /home/vagrant/.bashrc'
    #default.vm.provision "shell", path: "vagrant/bin/clean.sh"

    default.vm.provider "virtualbox" do |v|
       v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
       v.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
       v.customize ["modifyvm", :id, "--memory", 2048]
       v.customize ["setextradata", :id, "--VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
    end

    default.vm.provision "shell", inline: <<-EOC
        test -e /usr/local/bin/docker-compose || \\
        curl -sSL https://github.com/docker/compose/releases/download/1.9.0/docker-compose-`uname -s`-`uname -m` \\
          | sudo tee /usr/local/bin/docker-compose > /dev/null
        sudo chmod +x /usr/local/bin/docker-compose
    EOC

    default.vm.provision :shell, inline: 'cd /var/www/application/../ && ./install.sh'

  end


end
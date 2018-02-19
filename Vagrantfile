#If your Vagrant version is lower than 1.5, you can still use this provisioning
#by commenting or removing the line below and providing the config.vm.box_url parameter,
#if it's not already defined in this Vagrantfile. Keep in mind that you won't be able
#to use the Vagrant Cloud and other newer Vagrant features.
Vagrant.require_version ">= 1.5"

Vagrant.configure("2") do |config|

    config.vm.provider :virtualbox do |v|
        v.name = "framework"
        v.customize [
            "modifyvm", :id,
            "--name", "framework",
            "--memory", 1024,
            "--natdnshostresolver1", "on",
            "--cpus", 2,
        ]
    end

    config.vm.hostname = "framework"
    config.vm.box = "ubuntu/trusty64"

    config.vm.network :private_network, ip: "192.168.2.99"
    config.ssh.forward_agent = true

    config.vm.provision "ansible" do |ansible|
        ansible.playbook = "ansible/playbook.yml"
        ansible.inventory_path = "ansible/inventories/dev"
        ansible.limit = 'all'
    end

    # Files on your local machine
    config.vm.synced_folder ".", "/vagrant", :owner=> 'www-data', :group=>'www-data', :mount_options => ['dmode=775', 'fmode=775']
end

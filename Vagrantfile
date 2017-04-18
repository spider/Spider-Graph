# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "puphpet/ubuntu1404-x64"

  # For internet connectivity
  config.vm.provider "virtualbox" do |v|
      v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
      v.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
  end

  # Provision the Machine
  config.vm.provision :shell, inline: "sh -c /vagrant/CI/php-install.sh", env: Hash["SPIDER_DIR" => "/vargrant", "BUILD_DIR" => "/home/vagrant"]
  config.vm.provision :shell, inline: "sh -c /vagrant/CI/jdk8-install.sh", env: Hash["SPIDER_DIR" => "/vargrant", "BUILD_DIR" => "/home/vagrant"]
  config.vm.provision :shell, inline: "sh -c /vagrant/CI/neo4j/install.sh", env: Hash["SPIDER_DIR" => "/vargrant", "BUILD_DIR" => "/home/vagrant"]
  config.vm.provision :shell, inline: "sh -c /vagrant/CI/orient/install.sh", env: Hash["SPIDER_DIR" => "/vargrant", "BUILD_DIR" => "/home/vagrant"]
  config.vm.provision :shell, inline: "sh -c /vagrant/CI/gremlin-server/install.sh", env: Hash["SPIDER_DIR" => "/vargrant", "BUILD_DIR" => "/home/vagrant"]

  # Start databases every time
  config.vm.provision :shell, inline: "sh -c /vagrant/CI/start-services.sh", run: "always", env: Hash["SPIDER_DIR" => "/vargrant", "BUILD_DIR" => "/home/vagrant"]
end
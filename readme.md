# OMS Development VM (new) 
The other one is not deprecated, but it is no longer in use

### How do I get set up? ###

Please read the [official landing page](https://oms-project.atlassian.net) (FIXME) at the documentation central repository






* Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) and
  [Vagrant](https://www.vagrantup.com/downloads.html).
* Download [this file](FIXME) and launch it (FOR LINUX USERS ONLY). For windows users, if you want to virtualise linux keep in mind that the machine has to be powerful enough to virtualise at its inside.
* Visit FIXME in a browser to check if installation was successful.

### Remarks for Windows hosts ###

If you use Windows as host (i.e., as development platform), you have to be cautious about a few things:

* Be careful about too long filenames. Either clone at a high point of your folder structure (for example at `C:\aegee`), or use `git config --system core.longpaths true`(with Git 1.9.0 and above). See [here](http://stackoverflow.com/questions/22575662/filename-too-long-in-git-for-windows) for details.
* You MUST use LF line endings in git. This is true for all submodules as well! See [this article](https://help.github.com/articles/dealing-with-line-endings/) for details. These commands should do the trick:
```
    git config core.autocrlf false
    git config core.eol lf
    git submodule foreach --recursive "git config core.autocrlf false"
    git submodule foreach --recursive "git config core.eol lf"
    rm -rf *     # Careful: you know what this does!
    git checkout -- .
    git submodule update --recursive
```
* You also have to fix symlinks after cloning! See [this article](http://stackoverflow.com/questions/5917249/git-symlinks-in-windows) for more info.


### Setup of ports (FIXME) ###

|Host (your computer)|Service|Used by|Guest (the VM)|
|---|---|---|---|
|8888|HTTP|apache2|80|
|4444|LDAP|slapd|389|
|2222|SSH|sshd|22|
|---|---|---|---|
|8800|API|oms-core|8080|
|8801|consumer|oms-profiles-module|8081|

### Sharing the content of the VM directly so we can work from the host ###

|Your local folder|synced seamless in your VM at|
|---|---|---|---|
|ignore/oms-core|/srv/oms-core|
|ignore/oms-profiles-module|/srv/oms-profiles-module|

### Structure of files and where to touch to modify as you please ###

* Ports: Vagrantfile
* Synced folders: Vagrantfile
* Installation and provisioning of the VM: puppet/manifests/site.pp --> __in this file, there are two "classes" used that are defined as per below:__
  1.  Stuff about installation of LDAP: puppet/modules/aegee_ldap/manifests/init.pp
  2.  Stuff about installation of OMS: puppet/modules/aegee_oms_modules/manifests/init.pp

### Versions of Node.js and npm ###
As defined in the manifest, the versions are:

* node 4.1.2
* npm 2.14.4

(that version of npm is not defined, it is the one shipped with node 4.1.2)

### Puppet version on the guest ###
As defined in scripts/upgrade_puppet.sh, the release is *puppetlabs-release-trusty.deb* which is fixed at 

* 3.8.6


# OMS Development VM (new) 
The other one is not deprecated, but it is no longer in use

### How do I get set up? ###

Please read the [official landing page](https://oms-project.atlassian.net/wiki/spaces/OMS) at the documentation central repository


* Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) and
  [Vagrant](https://www.vagrantup.com/downloads.html).
* Follow the instruction at the bottom of [this page](https://oms-project.atlassian.net/wiki/display/PROV/The+installation+script%3A+usage) and launch it with your POSIX-compliant shell (= FOR LINUX/OSX USERS ONLY). For windows users, if you want to virtualise linux keep in mind that the machine has to be powerful enough to virtualise at its inside. And you may have to fight with the port forwarding.
* Visit localhost:8000 in a browser to check if installation was successful.

### Possibly other remarks for Windows hosts ###

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

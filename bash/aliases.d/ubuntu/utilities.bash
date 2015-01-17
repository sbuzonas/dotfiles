alias sysupdate='sudo apt-get update; sudo apt-get upgrade; sudo apt-get autoremove'
alias installed="dpkg --get-selections | grep -v deinstall | sed -e 's/\(.*\)\s*install/\1/'"

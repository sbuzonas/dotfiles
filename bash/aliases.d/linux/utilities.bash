alias flushdns="sudo /etc/init.d/dnsmasq restart"
alias primaryif="ip ro | grep -Eo 'via $IPV4_ADDR.*dev [^ ]*' | tr -s [:space:] | sed -e 's/.*dev \(.*\)/\1/' | tr '\n' ' ' | cut -d ' ' -f1"
alias localip="ip addr show $(primaryif) | sed -e '/^[^ ]/d' -e 's/.*inet \(.*\)\/.*/\1/' -e '/^ /d'"

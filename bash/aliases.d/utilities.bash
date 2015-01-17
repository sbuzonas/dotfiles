alias path='echo -e ${PATH//:/\\n}'
alias libpath='echo -e ${LD_LIBRARY_PATH//:/\\n}'

alias psx='ps -auxw | grep $1'
alias rehash='source ~/.bash_profile'
# Reload the shell (i.e. invoke as a login shell)
alias reload="exec $SHELL -l"
alias week='date +%V'
alias timer='echo "Timer started. Stop with Ctrl-D." && date && time cat && date'

# ROT13-encode text. Works for decoding, too! ;)
alias rot13='tr a-zA-Z n-za-mN-ZA-M'

# URL-encode strings
alias urlencode='python -c "import sys, urllib as ul; print ul.quote_plus(sys.argv[1]);"'

alias ipaddr='dig +short myip.opendns.com @resolver1.opendns.com'
IPV4_OCTET="(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])"
IPV4_ADDR="($IPV4_OCTET\.){3,3}$IPV4_OCTET"
IPV6_OCTET="[0-9a-fA-F]{1,4}"
IPV6_ADDR="(($IPV6_OCTET:){7,7}$IPV6_OCTET|($IPV6_OCTET:){1,7}:|($IPV6_OCTET:){1,6}:$IPV6_OCTET|($IPV6_OCTET:){1,5}(:$IPV6_OCTET){1,2}|($IPV6_OCTET:){1,4}(:$IPV6_OCTET){1,3}|($IPV6_OCTET:){1,3}(:$IPV6_OCTET){1,4}|($IPV6_OCTET:){1,2}(:$IPV6_OCTET){1,5}|$IPV6_OCTET:((:$IPV6_OCTET){1,6})|:((:$IPV6_OCTET){1,7}|:)|fe80:(:$IPV6_OCTET){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}($IPV4_ADDR)|($IPV6_OCTET:){1,4}:($IPV4_ADDR))"
alias ips="ifconfig -a | grep -Eo '((inet (addr:)?\s?$IPV4_ADDR)|(inet6 (addr:)?\s?$IPV6_ADDR))' | awk '{ sub(/inet6? (addr:)? ?/, \"\"); print}'"

alias whois="whois -h whois-servers.net"

# View HTTP traffic
alias sniff="sudo ngrep -d 'en2' -t '^(GET|POST) ' 'tcp and port 80'"
alias httpdump="sudo tcpdump -i en2 -n -s 0 -w - | grep -a -o -E \"Host\: .*|GET \/.*\""

# Canonical hex dump; some systems have this symlinked
command -v hd > /dev/null || alias hd="hexdump -C"

# use `md5` as a fallback for systems without `md5sum`
command -v md5sum > /dev/null || alias md5sum="md5"

# use `shasum` as a fallback for systems without `sha1sum`
command -v sha1sum > /dev/null || alias sha1sum="shasum"

# Ring the terminal bell, and put a badge on Terminal.app’s Dock icon
# (useful when executing time-consuming commands)
alias badge="tput bel"

# Intuitive map function
# For example, to list all directories that contain a certain file:
# find . -name .gitattributes | map dirname
alias map="xargs -n1"

# One of @janmoesen’s ProTip™s
for method in GET HEAD POST PUT DELETE TRACE OPTIONS; do
    alias "$method"="lwp-request -m '$method'"
done

# Kill all the tabs in Chrome to free up memory
# [C] explained: http://www.commandlinefu.com/commands/view/402/exclude-grep-from-your-grepped-output-of-ps-alias-included-in-description
alias chromekill="pgrep '[C]hrome Helper --type=renderer' | grep -v extension-process | awk '{print $1}' | xargs kill"

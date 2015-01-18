alias grep='grep --color=auto'
alias tree='tree -Csuh'
alias mkdir='mkdir -p'
alias more='less'
alias gurl='curl --compressed'

alias path='echo -e ${PATH//:/\\n}'
alias libpath='echo -e ${LD_LIBRARY_PATH//:/\\n}'

alias psx='ps -auxw | grep $1'
alias rehash='source ~/.bash_profile'
alias week='date +%V'

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

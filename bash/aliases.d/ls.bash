if ls --color > /dev/null 2>&1; then # GNU `ls`
    alias ls='command ls -h --color'
else
    alias ls='command ls -h -G'
fi
alias ll='ls -l'
alias l="ll -F --group-directories-first"
alias la="ll -af"
alias lsd="ll | grep --color=never '/$'"
alias lx='ll -XB'
alias lk='ll -Sr'
alias lt='ll -tr'
alias lc='lt -c'
alias lu='lt -u'
alias lm='ll | less'
alias lr='ll -R'
alias laa='ll -A'

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

alias grep="grep --color=auto"

if ls --color > /dev/null 2>&1; then # GNU `ls`
    colorflag="--color"
else
    colorflag="-G"
fi

alias l="ls -lhF ${colorflag}"
alias la="ls -lhaf ${colorflag}"

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

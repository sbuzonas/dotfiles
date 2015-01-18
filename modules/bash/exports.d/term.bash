if [[ $COLORTERM = gnome-* && $TERM = xterm ]]  && infocmp gnome-256color >/dev/null 2>&1; then
    export TERM=gnome-256color
elif [[ $TERM = linux || $TERM = fbterm ]] && infocmp fbterm >/dev/null 2>&1; then
    export TERM=fbterm
elif infocmp xterm-256color >/dev/null; then
    export TERM=xterm-256color
fi

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

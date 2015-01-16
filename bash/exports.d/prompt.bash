source_files $DOTFILES_SCRIPT_DIR/colours

# Set up prompts. Color code them for logins.
THIS_TTY=tty`ps aux | grep $$ | grep bash | awk '{ print $7 }'`
SESS_SRC=`who | grep $THIS_TTY | awk '{ print $6 }'`

SSH_FLAG=0
SSH_IP=`echo $SSH_CLIENT | awk '{ print $1 }'`
if [ $SSH_IP ] ; then
    SSH_FLAG=1
fi
SSH2_IP=`echo $SSH2_CLIENT | awk '{ print $1 }'`
if [ $SSH2_IP ] ; then
    SSH_FLAG=1
fi
if [ $SSH_FLAG -eq 1 ] ; then
    CONN=" \[$BASE0\]via \[$VIOLET\]ssh"
else
    CONN=
fi
export PROMPT_COMMAND="history -a; history -c; history -r; $PROMPT_COMMAND"
export PS1="\$(([[ \$? -ne 0 ]] && echo \"\[$MAGENTA\]✘ \") || echo \"\[$GREEN\]✔ \")\[$YELLOW\]\u \[$BASE0\]at \[$ORANGE\]$(hostname -f)$CONN \[$BASE0\]running \[$CYAN\]\j jobs\n\[$BASE0\]working in \[$BLUE\]\w\[$BASE0\]\$([[ -n \$(git branch 2> /dev/null) ]] && echo \" on \")\[$YELLOW\]\$(colour_parse_git_branch)\[$BASE0\]\n\$ \[$RESET\]"

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

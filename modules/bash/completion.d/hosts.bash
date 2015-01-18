function _complete_hostnames() {
    hosts=$(cat /etc/hosts | sed -e s/#.*//g | expand | tr -s [[:space:]] | sed -e /^$/d)
    if [ -r ~/.hosts ] ; then
	hosts="$hosts $(cat ~/.hosts | sed -e s/#.*//g | expand | tr -s [[:space:]] | sed -e /^$/d)"
    fi
    hosts_files='~/.ssh/known_hosts'
    if [ -r ~/.ssh/config ] ; then
	hosts_files="$hosts_files `cat ~/.ssh/config | grep "^[ ]*UserKnownHostsFile" | awk '{print $2}' | sort | uniq`"
	hosts="$hosts $(cat ~/.ssh/config | grep "^Host " | awk '{$1="";print $0}' | sed /\*/d)"
    fi

    for f in $hosts_files ; do
    	eval f=$f
	if [ -r "$f" ] ; then
	    hosts="$hosts $(cat $f | cut -f 1 -d ' ' | sed -e 's/,/ /g')"
	fi
    done

    hosts=`echo -e $(echo $hosts | sed -e 's/ /\\\\n/g') | sort | uniq`

    COMPREPLY=()
    cur="${COMP_WORDS[COMP_CWORD]}"

    COMPREPLY=( $(compgen -W "${hosts}" -- $cur))
    return 0
}
complete -F _complete_hostnames curl dig scp sftp ssh wget

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

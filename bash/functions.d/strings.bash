function lowercase() {
    if [ -z "$*" ] ; then
	while read data ; do
	    printf "$data" | tr '[:upper:]' '[:lower:]'
	done
    else
	printf "$*" | tr '[:upper:]' '[:lower:]'
    fi
}

function uppercase() {
    if [ -z "$*" ] ; then
	while read data ; do
	    printf "$data" | tr '[:lower:]' '[:upper:]'
	done
    else
	printf "$*" | tr '[:lower:]' '[:upper:]'
    fi
}

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

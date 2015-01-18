function async_run() {
    {
	$1 &> /dev/null
    }&
}

function source_files() {
    for f in $* ; do
	[ -f "$f" ] && [ -r "$f" ] && . "$f"
    done
}

function source_file_alternatives() {
    for v in OS OS_RELEASE LSB_DISTRO LSB_RELEASE LSB_CODENAME ; do
	eval "v=\$$v"
	if [ -n "$v" ] ; then
	    f="$1/$v$2"
	    [ -f "$f" ] && [ -r "$f"] && . "$f"
	fi
    done
}

function source_directory() {
    source_files `find $1 -maxdepth 1 -name '*.bash'`
    for v in OS OS_RELEASE LSB_DISTRO LSB_RELEASE LSB_CODENAME ; do
	eval "v=\$$v"
	if [ -n "$v" ] ; then
	    d="$1/$v"
	    [ -d "$d" ] && source_files `find "$d" -maxdepth 1 -name '*.bash'`
	fi
    done
}

# From http://stackoverflow.com/questions/370047/#370255
function path_remove() {
    SAVEIFS=$IFS
    IFS=:
    # convert it to an array
    t=($PATH)
    unset IFS
    # perform any array operations to remove elements from the array
    t=(${t[@]%%$1})
    IFS=:
    # output the new array
    echo "${t[*]}"
    IFS=$SAVEIFS
}

function path_prepend() {
    echo "$1:$(path_remove $1)"
}

function path_append() {
    echo "$(path_remove $1):$1"
}

# From http://stackoverflow.com/questions/4023830/#4024263
verlte() {
    [  "$1" = "`echo -e "$1\n$2" | $SORT_EXEC -V | head -n1`" ]
}

verlt() {
    [ "$1" = "$2" ] && return 1 || verlte $1 $2
}

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

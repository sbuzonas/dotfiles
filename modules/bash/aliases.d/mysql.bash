if [[ -n "$(type -P mysql)" && -r "$HOME/.my.cnf" ]] ; then
    for suffix in `grep '^\[client' $HOME/.my.cnf | sed -e 's/\[client\([^]]*\)\]/\1/' -e /^$/d` ; do
	[[ -z "$(type $suffix 2>/dev/null)" ]] && alias "$suffix"="mysql --defaults-group-suffix=$suffix"
	[[ -z "$(type ${suffix}dump 2>/dev/null)" ]] && alias "${suffix}dump"="mysqldump --defaults-group-suffix=$suffix"
    done
fi

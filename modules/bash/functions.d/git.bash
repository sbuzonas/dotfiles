function colour_parse_git_dirty() {
    [[ "$(git rev-parse --is-inside-work-tree 2> /dev/null)" == "true" ]] || return 0
    [[ -n "$(git status --porcelain)" ]] && echo " $MAGENTA*"
}

function colour_parse_git_branch() {
    [[ -n $(git branch 2> /dev/null) ]] || return 0

    local BRANCH=`git branch --no-color | sed -e '/^[^*]/d' -e 's/* \(.*\)/\1/'`
    local TRACKING=""

    if [[ $BRANCH == "(no branch)" ]] ; then
	BRANCH="(detached from `git rev-parse --short HEAD`)"
    fi

    git config branch.$BRANCH.remote &> /dev/null
    if [[ $? -ne 0 ]] ; then
	IS_TRACKING=false
    else
	IS_TRACKING=true

	local GIT_DIR=`git rev-parse --git-dir`
	if [[ $GIT_DIR == "." ]] ; then
	    GIR_DIR=`pwd`
	fi

	local FETCH_HEAD="$GIT_DIR/FETCH_HEAD"

	if [[ ! -e "$FETCH_HEAD" || -e `find "$FETCH_HEAD" -mmin +${GIT_FETCH_TIMEOUT-5}` ]] ; then
	    async_run "git fetch --all --quiet"
	    disown -h
	fi

	local TRACKING_STATUS=`git branch -vv --no-color | sed -e '/^[^*]/d' -e 's/.*\[\(.*\)\].*/\1/'`
	local TRACKING_BRANCH=`echo $TRACKING_STATUS | cut -d':' -f1`
	local TRACKING_AHEAD=`echo $TRACKING_STATUS | sed -e 's/.*ahead \([0-9]*\).*/\1/'`
	local TRACKING_BEHIND=`echo $TRACKING_STATUS | sed -e 's/.*behind \([0-9]*\).*/\1/'`

	local NUM_REGEX='^[0-9]+$'

	if ! [[ $TRACKING_AHEAD =~ $NUM_REGEX ]] ; then
	    TRACKING_AHEAD="0"
	fi

	if ! [[ $TRACKING_BEHIND =~ $NUM_REGEX ]] ; then
	    TRACKING_BEHIND="0"
	fi

	TRACKING="${BASE0} tracking ${CYAN}$TRACKING_BRANCH ${BASE0}[${YELLOW}↑ $TRACKING_AHEAD${BASE0}|${ORANGE}↓ $TRACKING_BEHIND${BASE0}]"
    fi

    if [[ -n $(colour_parse_git_dirty) ]] ; then
	COLOUR_BRANCH="$MAGENTA$BRANCH"
    else
	COLOUR_BRANCH="$GREEN$BRANCH"
    fi

    echo "$COLOUR_BRANCH$TRACKING$(colour_parse_git_dirty)"
}

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

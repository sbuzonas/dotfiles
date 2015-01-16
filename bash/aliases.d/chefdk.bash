function knife {
    `PATH="$(path_remove $HOME/.rbenv/shims)" which knife` $@
}

function berks {
    `PATH="$(path_remove $HOME/.rbenv/shims)" which berks` $@
}

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

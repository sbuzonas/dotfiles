PATH="$(path_prepend /usr/local/bin)"
PATH="$(path_prepend $HOME/.rbenv/bin)"
PATH="$(path_prepend $HOME/bin)"

PATH="$(path_append $DOTFILES_DIR/bin)"

export PATH

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

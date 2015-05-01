PATH="$(path_prepend /usr/local/bin)"
PATH="$(path_prepend $DOTFILES_DIR/bin)"
PATH="$(path_prepend $HOME/bin)"
PATH="$(path_prepend $HOME/.rbenv/bin)"
PATH="$(path_prepend $HOME/.composer/vendor/bin)"
PATH="$(path_prepend ./bin)"

export PATH="$(deduplicate_path $PATH)"

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

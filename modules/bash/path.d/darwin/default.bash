PATH="$(path_prepend `brew --prefix coreutils`/libexec/gnubin)"
PATH="$(path_prepend `brew --prefix gettext`/bin)"

export PATH="$(deduplicate_path $PATH)"

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

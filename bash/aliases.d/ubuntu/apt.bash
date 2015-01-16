alias apt-installed="dpkg --get-selections | grep -v deinstall | sed -e 's/\(.*\)\s*install/\1/'"

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

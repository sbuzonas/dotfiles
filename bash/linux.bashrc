LSB_DISTRO=$(lsb_release -si | lowercase)
LSB_RELEASE=$LSB_DISTRO-$(lsb_release -sr)
LSB_CODENAME=$LSB_DISTRO-$(lsb-release -sc)

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

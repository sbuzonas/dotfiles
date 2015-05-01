function knife {
    `PATH="$(path_remove $HOME/.rbenv/shims)" which knife` $@
}

function berks {
    `PATH="$(path_remove $HOME/.rbenv/shims)" which berks` $@
}

alias cg-app='chef generate app -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-cookbook='chef generate cookbook -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-recipe='chef generate recipe -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-attribute='chef generate attribute -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-template='chef generate template -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-file='chef generate file -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-lwrp='chef generate lwrp -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-repo='chef generate repo -I mit -C "Steve Buzonas" -m steve@fancyguy.com'
alias cg-policyfile='chef generate policyfile -I mit -C "Steve Buzonas" -m steve@fancyguy.com'

alias cli-app='chef generate app -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-cookbook='chef generate cookbook -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-recipe='chef generate recipe -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-attribute='chef generate attribute -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-template='chef generate template -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-file='chef generate file -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-lwrp='chef generate lwrp -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-repo='chef generate repo -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'
alias cli-policyfile='chef generate policyfile -I all_rights -C "Carnegie Learning, Inc." -m sbuzonas@carnegielearning.com -g ~/.chef/cli_chef_generator/'

# Local Variables:
# coding: utf-8
# mode: sh
# sh-shell: bash
# End:

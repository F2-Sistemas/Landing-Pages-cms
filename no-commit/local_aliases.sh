## dk-/sail
# alias cmd_base='dk- exec php'
alias cmd_base='sail'
alias php='cmd_base php'
alias artisan='cmd_base php artisan'
alias tinker='cmd_base php artisan tinker'
alias composer='cmd_base composer'
alias dev-tinker='cmd_base php artisan tinker --env=devdb'
alias dk-bash='cmd_base bash'

alias branches='BRANCHES="dev master" BRANCH_TO_CHECKOUT=dev bash ~/bash_files/binary_files/clear-branches.sh'

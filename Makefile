install :
		composer install
		yarn
		yarn encore dev

sass :
		yarn encore dev --watch
init :
		bin/console d:d:c
		bin/console d:s:u --force

install :
	composer install
	php bin/console ckeditor:install
	php bin/console assets:install public
	yarn
	yarn encore dev

sass :
	yarn encore dev --watch
init :
	bin/console d:d:c
	bin/console d:s:u --force

fixture :
	bin/console d:d:d --force
	bin/console d:d:c
	bin/console d:s:u --force
	bin/console d:f:l --append
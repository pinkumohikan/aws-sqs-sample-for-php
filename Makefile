
.env:
	cp .env.example .env

composer.phar:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"


.PHONY: help install
help:
	@cat Makefile

install: .env composer.phar
	

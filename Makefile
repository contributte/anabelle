
.PHONY: install qa cs csf phpstan tests coverage-clover coverage-html

install:
	composer update

qa: phpstan cs

cs:
	vendor/bin/phpcs --standard=vendor/gamee/php-code-checker-rules/ruleset.xml --extensions=php,phpt --tab-width=4 --ignore=temp -sp src

csf:
	vendor/bin/phpcbf --standard=vendor/gamee/php-code-checker-rules/ruleset.xml --extensions=php,phpt --tab-width=4 --ignore=temp -sp src

phpstan:
	vendor/bin/phpstan analyse src -c vendor/gamee/php-code-checker-rules/phpstan.neon --level 7

tests:
	vendor/bin/tester tests -C
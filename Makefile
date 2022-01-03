.PHONY: install qa cs csf phpstan tests coverage-clover coverage-html

install:
	composer update

qa: phpstan cs

cs:
	vendor/bin/phpcs --standard=vendor/contributte/code-rules/paveljanda/ruleset.xml --extensions=php,phpt --tab-width=4 --ignore=temp -sp src

csf:
	vendor/bin/phpcbf --standard=vendor/contributte/code-rules/paveljanda/ruleset.xml --extensions=php,phpt --tab-width=4 --ignore=temp -sp src

phpstan:
	vendor/bin/phpstan analyse src -c vendor/contributte/code-rules/paveljanda/phpstan.neon --level 7

tests:
	vendor/bin/tester tests -C

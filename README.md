# PHPCS GitLab Report

[![Build Status](https://travis-ci.org/sateshpersaud/phpcs-gitlab-report.svg?branch=master)](https://travis-ci.org/sateshpersaud/phpcs-gitlab-report)

## Usage

### Code Quality

```yaml
# .gitlab-ci.yml

code_quality:
  image: lorisleiva/laravel-docker:7.2
  stage: test
  script:
    - php ./vendor/bin/phpcs \
          --report=\\Satesh\\Phpcs\\CodeQualityReport \
          --standard=PSR2 \
          --basepath=./ \
          --report-file=gl-code-quality-report.json \
          src
  artifacts:
    reports:
      codequality: gl-code-quality-report.json
    paths:
      - ./gl-code-quality-report.json
  allow_failure: true
```


### SAST

*Note: use with [pheromone/phpcs-security-audit](https://github.com/FloeDesignTechnologies/phpcs-security-audit)*

```yaml
# .gitlab-ci.yml

phpcs-security-audit-sast:
  stage: test
  script:
    - php ./vendor/bin/phpcs \
          --report=\\Satesh\\Phpcs\\SecurityReport \
          --standard=./vendor/pheromone/phpcs-security-audit/example_base_ruleset.xml \
          --basepath=./ \
          --report-file=gl-sast-report.json \
          src
  artifacts:
    reports:
      sast: gl-sast-report.json
    paths:
      - ./gl-sast-report.json
  allow_failure: true
```

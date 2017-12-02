# PHP Include Test

This project was designed to test the performance of PHP file includes using `require`, `require_once`, and `include` by creating a set of test files to include, timing each include method, and outputting the information. A shell script is provided to run the same tests on the host environment, a Docker container using a volume mapping, and a Docker container created from an image containing the test scripts without any volume mapping.

## Usage

- Clone the repo
- Ensure docker is installed and running
- Ensure php (Dockerfile is using php 7.1 currently) is installed on host, using a different version will yield flawed results between host and Docker.
- Run `./docker_php_include_test.sh` to test include functions only, `./docker_php_include_test.sh -m` to run a memory test along in include files as well.

## Contributing

This script was created based on some tests run by https://github.com/MarcelloOr in https://github.com/docker-library/php/issues/493 to help confirm bottlenecks in local vs Docker volume mapped paths vs Docker Images w/o volumes. Pull requests and issues are welcomed, posting some comparison tests w/different hosts and docker setups would be helpful.

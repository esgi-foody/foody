uname_S := $(shell sh -c 'uname -s 2>/dev/null || echo not')
ifeq ($(uname_S),Linux)
	HOSTESS_ARCH = linux_amd64
else ifeq ($(uname_S),Darwin)
	HOSTESS_ARCH = darwin_amd64
endif
HOSTESS_URL = https://github.com/cbednarski/hostess/releases/download/v0.3.0/hostess_$(HOSTESS_ARCH)

HOSTESS_BIN	= ./bin/hostess
SUDO		= /usr/bin/sudo

hostess-download : ## Download hostess and make it runnable
	curl $(HOSTESS_URL) -o $(HOSTESS_BIN) -L
	chmod +x $(HOSTESS_BIN)

hosts :		### (Alias=h) Download hostess if necessary and apply hosts listed in hosts.json
ifeq ($(shell [ -e $(HOSTESS_BIN) ] && echo 1 || echo 0 ), 0)
	@make hostess-download
endif
	$(SUDO) $(HOSTESS_BIN) apply ./hosts.json

install :
	mkdir -p docker/data
	composer install
	make hosts

up :
	docker-compose up --build

down : 
	docker-compose down
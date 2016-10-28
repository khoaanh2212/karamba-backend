DOCKER_REGISTRY?=localhost:5000
TAG?=latest
PROJECT?=carambacars
IMAGE=$(DOCKER_REGISTRY)/$(PROJECT)
IMAGE_TAG=$(IMAGE):$(TAG)
IMAGE_LATEST=$(IMAGE):latest
EXTERNAL_IP?=127.0.0.1
DEBIAN_JESSIE_SOURCES_MIRROR?=
COMPOSE=IMAGE_NAME=$(IMAGE_LATEST) SOURCES_MIRROR="$(DEBIAN_JESSIE_SOURCES_MIRROR)" docker-compose -f compilation_environment.yml

build:
	$(COMPOSE) stop
	yes | docker rm -v karambamysql || true
	yes | $(COMPOSE) rm -v
	$(COMPOSE) build
	$(COMPOSE) up -d
	docker exec -i karambacars bash /opt/runTests.sh karambamysql
	$(COMPOSE) stop
	yes | $(COMPOSE) rm
	docker tag $(IMAGE_LATEST) $(IMAGE_TAG)
	docker push $(IMAGE_TAG)
delete:
	docker rmi $(IMAGE_TAG)


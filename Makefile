docker-up:
	docker network create microservice_external || true
	docker-compose up -d

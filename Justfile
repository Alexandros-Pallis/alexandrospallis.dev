# Build the docker image without using the cache
build-fresh:
	docker compose build --pull --no-cache

# run the docker compose with wait for the services to be ready
up:
	docker compose up --wait

# Stop and remove the docker compose services and remove any orphan containers
down:
    docker compose down --remove-orphans

# Run the docker compose with wait for the services to be ready and remove any orphan containers
restart:
    just down
    just up

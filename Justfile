build-fresh:
	docker compose build --pull --no-cache
up:
	docker compose up --wait
down:
    docker compose down --remove-orphans
restart:
    just down
    just up

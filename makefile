docker-run:
	docker compose up -d

docker-run-prod:
	sudo docker compose up -d
	
run:
	composer run dev

run-vite:
	npm run dev

mysql-console:
	sudo docker exec -it skincare-catalog-db-1 bash

logs:
	pwsh -Command "Get-Content -Tail 50 storage/logs/laravel.log"
.PHONY: run down

#Startar docker container
run:down
	@echo "Starting Laravel Sail..."
	@vendor/bin/sail up -d

#Stänger docker container
down:
	@echo "Stopping Laravel Sail..."
	@vendor/bin/sail down

#Gör en migrering till databasen. 
migration:
	@echo "Making a migration"
	@./vendor/bin/sail artisan migrate


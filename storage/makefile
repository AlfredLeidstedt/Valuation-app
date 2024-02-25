.PHONY: run down

#Startar docker container
run:
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

#Startar en dockermiljö för n8n. Workflows och inställningar sparas lokalt. 
startN8n:
	@docker volume create n8n_data
	@docker run -it --rm --name n8n -p 5678:5678 -v n8n_data:/home/node/.n8n docker.n8n.io/n8nio/n8n

#Hämtar senaste versionen av n8n. 
updateN8n:
	@docker pull docker.n8n.io/n8nio/n8n

#Stoppar docker containern för n8n
stopN8n:
	@docker stop n8n

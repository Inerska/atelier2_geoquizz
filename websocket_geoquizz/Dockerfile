# Utilise l'image officielle de Node.js comme image de base
FROM node:alpine

# Définit le répertoire de travail dans le conteneur
WORKDIR /usr/src/app

# Copie les fichiers `package.json` et `package-lock.json` (si disponible)
COPY package*.json ./

# Installe les dépendances du projet
RUN npm install

# Copie les fichiers et dossiers du projet dans le répertoire de travail du conteneur
COPY . .

# Compile le code TypeScript en JavaScript
RUN npm run build

# Expose le port sur lequel le serveur express écoute
EXPOSE 5200

# Commande exécutée au démarrage du conteneur
CMD [ "node", "dist/server.js" ]
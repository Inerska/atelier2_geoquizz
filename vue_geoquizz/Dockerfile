FROM node:18-alpine AS build
WORKDIR /app

COPY package*.json ./
RUN npm install -g pnpm
RUN pnpm install

COPY . .

CMD ["npm", "run", "dev", "--", "--host"]

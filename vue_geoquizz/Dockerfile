FROM node:18-alpine AS build
WORKDIR /app

COPY package*.json pnpm-lock.yaml ./

RUN npm install -g pnpm && pnpm install

COPY . .

CMD ["npm", "run", "dev", "--", "--host"]

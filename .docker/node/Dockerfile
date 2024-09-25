FROM node:18-alpine

WORKDIR /app

COPY package.json /app

RUN cd /app && npm install

#RUN npm run build

COPY /app .

CMD ["npm", "run", "build"]

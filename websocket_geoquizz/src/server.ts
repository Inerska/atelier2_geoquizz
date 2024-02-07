import express from 'express';
import http from 'http';
import WebSocket from 'ws';

const app = express();
const server = http.createServer(app);

const wss = new WebSocket.Server({ server });

wss.on('connection', (ws: WebSocket) => {
    console.log('Nouvelle connexion WebSocket');

    ws.on('message', function message(data, isBinary) {
        const message = isBinary ? data : data.toString();
        console.log(message)

        // if (message === 'newGame')
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });

    ws.send('Connexion WebSocket établie (backend)');
});



// Démarrage du serveur Express sur le port 5200
const port = 5200;
server.listen(port, () => {
    console.log(`Serveur démarré sur http://localhost:${port}`);
});

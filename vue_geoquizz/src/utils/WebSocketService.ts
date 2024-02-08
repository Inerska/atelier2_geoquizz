class WebSocketService {
    private ws: WebSocket | null = null;
    private url: string = '';
    private reconnectAttempts: number = 1;
    private maxReconnectAttempts: number = 3;
    private reconnectInterval: number = 5000;

    connect(url: string) {
        this.url = url;

        this.ws = new WebSocket(url);

        this.ws.onopen = () => {
            console.log('Connexion WebSocket établie.');
        };

        this.ws.onerror = (error) => {
            console.error('Erreur WebSocket:', error);

            this.tryReconnect();
        };

        this.ws.onmessage = (event) => {
            console.log('Message reçu:', event.data);
            if (event.data === 'newGame') {
                console.log('Nouvelle partie');
            }
        };

        this.ws.onclose = () => {
            console.log('Connexion WebSocket fermée. Tentative de reconnexion...');
        };
    }

    sendMessage(message: string) {
        console.log('Envoi de message:', message);
        if (this.ws?.readyState === WebSocket.OPEN) {
            this.ws.send(message);
        } else {
            console.error('WebSocket n\'est pas connecté.');
            this.tryReconnect(() => this.sendMessage(message));
        }
    }

    private tryReconnect(callback?: () => void) {
        if (!this.url || this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.log('Tentative de reconnexion abandonnée ou nombre maximum atteint.');
            return;
        }

        if (this.ws && (this.ws.readyState === WebSocket.CONNECTING || this.ws.readyState === WebSocket.OPEN)) {
            console.log('WebSocket est déjà connecté ou en cours de connexion.');
            return;
        }

        console.log(`Tentative de reconnexion WebSocket... (${this.reconnectAttempts + 1})`);
        setTimeout(() => {
            this.connect(this.url);
            this.reconnectAttempts++;
            if (callback) {
                callback();
            }
        }, this.reconnectInterval);
    }
}

export const ws = new WebSocketService();
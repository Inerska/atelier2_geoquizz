class WebSocketService {
    private ws: WebSocket | null = null;
    constructor() {
        this.connect('ws://localhost:5200')
    }

    connect(url: string) {
        this.ws = new WebSocket(url);

        this.ws.onopen = () => {
            console.log('Connexion WebSocket établie.');
            this.ws.send('message');
        };

        this.ws.onerror = (error) => {
            console.error('Erreur WebSocket:', error);
        };

        this.ws.onmessage = (event) => {
            console.log('Message reçu:', event.data);
        };

        this.ws.onclose = () => {
            console.log('Connexion WebSocket fermée.');
        };
    }

    sendMessage(message: string) {
        if (this.ws?.readyState === WebSocket.OPEN) {
            this.ws.send(message);
        } else {
            console.error('WebSocket n\'est pas connecté.');
        }
    }
}

export const ws = new WebSocketService()


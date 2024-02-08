
class WebSocketService {
    private ws: WebSocket | null = null;
    private url: string = '';

    connect(url: string) {
        this.url = url;
        this.ws = new WebSocket(url);

        this.ws.onopen = () => {
            console.log('Connexion WebSocket établie.');

            // Vous pouvez activer l'envoi de message automatique ici si besoin
            // this.sendMessage('Votre message');
        };

        this.ws.onerror = (error) => {
            console.error('Erreur WebSocket:', error);

            // Tentative de reconnexion
            setTimeout(() => this.reconnect(), 5000);
        };

        this.ws.onmessage = (event) => {
            console.log('Message reçu:', event.data);
            if (event.data === 'newGame') {

            }
        };

        this.ws.onclose = () => {
            console.log('Connexion WebSocket fermée. Tentative de reconnexion...');

            // Tentative de reconnexion
            setTimeout(() => this.reconnect(), 5000);
        };
    }

    sendMessage(message: string) {
        console.log('Envoi de message:', message);
        if (this.ws?.readyState === WebSocket.OPEN) {
            this.ws.send(message);
        } else {
            console.error('WebSocket n\'est pas connecté.');
            // Tentative de reconnexion avant de renvoyer le message
            this.reconnect(() => this.sendMessage(message));
        }
    }

    // Fonction pour gérer la reconnexion
    private reconnect(callback?: () => void) {
        if (!this.url) return;

        // Vérifie si WebSocket est déjà en train de se connecter ou est déjà ouvert
        if (this.ws && (this.ws.readyState === WebSocket.CONNECTING || this.ws.readyState === WebSocket.OPEN)) {
            console.log('WebSocket est déjà connecté ou en cours de connexion.');
            
            if (callback) {
                setTimeout(callback, 1000); // Attend un peu pour s'assurer que la connexion est établie
            }
            return;
        }

        console.log('Tentative de reconnexion WebSocket...');
        this.connect(this.url);

        // Exécute un callback après la reconnexion si nécessaire
        if (callback) {
            setTimeout(callback, 1000); // Attend un peu pour s'assurer que la connexion est établie
        }
    }
}

export const ws = new WebSocketService()

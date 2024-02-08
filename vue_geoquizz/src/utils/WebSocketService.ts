import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

class WebSocketService {
    private ws: WebSocket | null = null;
    private url: string = '';
    private reconnectAttempts: number = 1;
    private maxReconnectAttempts: number = 3;
    private reconnectInterval: number = 5000; // Délai entre les tentatives de reconnexion en millisecondes

    connect(url: string) {
        this.url = url;

        this.ws = new WebSocket(url);

        this.ws.onopen = () => {
            console.log('Connexion WebSocket établie.');
            toast.success('Connexion WebSocket établie.', {
                autoClose: 3000,
                position: 'bottom-right'
            });
        };

        this.ws.onerror = (error) => {
            console.error('Erreur WebSocket:', error);

            toast.error(`Erreur WebSocket. Tentative de reconnexion... ${this.reconnectAttempts}/${this.maxReconnectAttempts}`, {
                autoClose: 3000,
                position: 'bottom-right'
            });
            this.tryReconnect();
        };

        this.ws.onmessage = (event) => {
            console.log('Message reçu:', event.data);
            if (event.data === 'newGame') {
                toast.info("Quelqu'un a lancé une partie !", {
                    autoClose: 3000,
                    closeButton: true,
                    theme: 'light',
                    pauseOnHover: false,
                    position: 'bottom-right'
                });
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
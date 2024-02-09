import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

class WebSocketService {
    private ws: WebSocket | null = null;
    private url: string = 'ws://localhost:5200';
    private reconnectAttempts: number = 1;
    private maxReconnectAttempts: number = 3;
    private reconnectInterval: number = 5000;

    init() {
        this.connect();
    }

    private connect() {
        this.ws = new WebSocket(this.url);

        this.ws.onopen = () => {
            console.log('Connexion WebSocket établie.');
            toast.success('Connexion WebSocket établie.', {
                autoClose: 2000,
                closeButton: true,
                theme: 'light',
                pauseOnHover: false,
                position: 'bottom-right'
            });
        };

        this.ws.onerror = (error) => {
            console.error('Erreur WebSocket:', error);

            toast.error(`Erreur WebSocket. Tentative de reconnexion... ${this.reconnectAttempts}/${this.maxReconnectAttempts}`, {
                autoClose: 5000,
                closeButton: true,
                theme: 'light',
                pauseOnHover: false,
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
            if ((event.data as string).startsWith('endGame')) {
                toast.info(`Quelqu'un a finit une partie avec un score de ${event.data.split('&')[1]} !`, {
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
            console.log('WebSocket est connecté. Envoi du message.')
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

        if (this.ws && this.ws.readyState === WebSocket.OPEN) return;

        if (this.ws && (this.ws.readyState === WebSocket.CONNECTING)) {
            console.log('WebSocket est en cours de connexion.');
            setTimeout(() => {
                if (callback) {
                    callback();
                }
            }, this.reconnectInterval);
            return;
        }

        console.log(`Tentative de reconnexion WebSocket... (${this.reconnectAttempts + 1})`);
        setTimeout(() => {
            this.connect();
            this.reconnectAttempts++;
            if (callback) {
                callback();
            }
        }, this.reconnectInterval);
    }
}

export const ws = new WebSocketService();
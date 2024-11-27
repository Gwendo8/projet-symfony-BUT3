// Importation de Bootstrap (JS et CSS)
import 'bootstrap'; // Assurez-vous que Bootstrap est installé via npm
import 'bootstrap/dist/css/bootstrap.min.css'; // Importer le CSS de Bootstrap

// Importation de Stimulus
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Initialisation de l'application Stimulus
export const app = startStimulusApp(
    require.context('./controllers', true, /\.([jt]sx?|css)$/)
);
startStimulusApp();

// Test JavaScript simple
console.log("Hello, World!");
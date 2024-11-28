import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = ["total", "items"];

    connect() {
        console.log("Contrôleur cart connecté");
      }
    
      disconnect() {
        console.log("Contrôleur cart déconnecté");
      }

    // Fonction pour ajouter un produit au panier
    async addToCart(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

        const apiUrl = window.location.origin + '/panier/ajouter';

        try {
            const response = await axios.post(apiUrl, formData);
            this.updateCart(response.data);
        } catch (error) {
        }
    }

    // Fonction pour supprimer un produit du panier
    async removeFromCart(event) {
        event.preventDefault();
        const productId = event.target.dataset.productId;
    
        if (!productId) {
            alert("Identifiant produit manquant.");
            return;
        }
    
        const apiUrl = window.location.origin + `/panier/supprimer/${productId}`;
        console.log('URL de la requête:', apiUrl);
    
        try {
            const response = await axios.post(apiUrl);
            if (response.data.success) {
                this.updateCart(response.data);
            } else {
                alert(response.data.error || "Erreur inconnue lors de la suppression.");
            }
        } catch (error) {
            console.error('Erreur lors de la suppression du panier:', error);
            alert("Une erreur est survenue lors de la suppression du panier.");
        }
    }

    // Fonction pour mettre à jour la quantité d'un produit dans le panier
    async updateQuantity(event) {
        event.preventDefault();
    
        const form = event.currentTarget;
        const productId = form.querySelector('input[name="quantity"]').dataset.productId;
    
        try {
            // Envoi de la requête pour mettre à jour la quantité du produit dans le panier
            const response = await axios.post(form.action, new FormData(form));
    
            // Vérification du contenu de la réponse
            console.log("Réponse reçue:", response.data);
    
            // Mise à jour du panier avec les nouveaux éléments
            if (this.hasItemsTarget && response.data.itemsHTML) {
                this.itemsTarget.innerHTML = response.data.itemsHTML;
            }
    
            // Mise à jour du total
            if (this.hasTotalTarget && response.data.total) {
                this.totalTarget.innerText = `${response.data.total} €`;
            }
    
            console.log(`Quantité modifiée pour le produit ${productId}`);
        } catch (error) {
            // Affichage de l'erreur détaillée pour la gestion des erreurs côté client
            console.error("Erreur lors de la mise à jour de la quantité.", error);
    
            // Optionnel: Log l'erreur pour mieux comprendre la réponse du serveur
            if (error.response) {
                // Affiche les détails du serveur, comme les logs du côté backend
                console.error("Réponse du serveur:", error.response);
                console.error("Détails de l'erreur 500:", error.response.data);
            }
        }
    }
    updateCart(data) {
        if (data && data.itemsHTML && data.total !== undefined) {
            // Mettez à jour uniquement le contenu spécifique des éléments du panier
            this.itemsTarget.innerHTML = data.itemsHTML; // Met à jour toute la section des produits
            this.totalTarget.innerText = `Total: ${data.total} €`; // Met à jour le total
            
            // Assurez-vous que les boutons de modification et la quantité restent intactes
            document.querySelectorAll('.update-quantity-btn').forEach(button => {
                button.addEventListener('click', this.updateQuantity);  // Attache l'événement de mise à jour de quantité
            });
        } else {
            alert("Une erreur est survenue lors de la mise à jour du panier.");
        }
    }
}
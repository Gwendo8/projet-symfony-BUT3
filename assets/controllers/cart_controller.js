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

        const apiUrl = window.location.origin + `/panier/supprimer/${productId}`;
        console.log('URL de la requête:', apiUrl);

        try {
            const response = await axios.post(apiUrl);
            this.updateCart(response.data);
        } catch (error) {
            console.error('Erreur lors de la suppression du panier:', error);
            alert("Erreur lors de la suppression du panier.");
        }
    }

    // Fonction pour mettre à jour la quantité d'un produit dans le panier
    async updateQuantity(event) {
        event.preventDefault();
    
        const quantityInput = event.target.querySelector('input');
        const productId = quantityInput.dataset.productId;
        const quantity = parseInt(quantityInput.value, 10);
    
        console.log("Quantité envoyée :", quantity);
        console.log("Product ID :", productId);
    
        if (isNaN(quantity) || quantity <= 0) {
            alert("La quantité doit être un nombre valide et strictement supérieure à 0.");
            return;
        }
    
        const apiUrl = window.location.origin + `/panier/modifier/${productId}`;
        console.log('URL de la requête:', apiUrl);
    
        const formData = new FormData();
        formData.append('quantity', quantity);
    
        try {
            const response = await axios.post(apiUrl, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
    
            console.log("Réponse de la requête :", response.data);
    
            if (response.data.success) {
                this.updateCart(response.data);
            } else {
                alert(response.data.message || 'Erreur lors de la mise à jour du panier.');
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour de la quantité :', error);
            alert("Une erreur est survenue lors de la mise à jour de la quantité.");
        }
    }

    // Mettre à jour l'affichage du panier
    updateCart(data) {
        // Mettre à jour le HTML des articles du panier
        document.querySelector('[data-cart-target="items"]').innerHTML = data.itemsHTML;
    
        // Mettre à jour le total du panier
        document.querySelector('[data-cart-target="total"]').innerText = `Total: ${data.total} €`;
    }
}
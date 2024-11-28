import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = ["total", "items", "cartCount"];

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
            console.error('Erreur lors de l\'ajout au panier', error);
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
            const response = await axios.post(form.action, new FormData(form));

            console.log("Réponse reçue:", response.data);

            if (this.hasItemsTarget && response.data.itemsHTML) {
                this.itemsTarget.innerHTML = response.data.itemsHTML;
            }

            if (this.hasTotalTarget && response.data.total) {
                this.totalTarget.innerText = `${response.data.total} €`;
            }

            if (this.hasCartCountTarget && response.data.cartCount !== undefined) {
                this.cartCountTarget.innerText = response.data.cartCount;
            }

            console.log(`Quantité modifiée pour le produit ${productId}`);
        } catch (error) {
            console.error("Erreur lors de la mise à jour de la quantité.", error);
        }
    }

    // Fonction pour mettre à jour les informations du panier après une action
    updateCart(data) {
        if (data && data.itemsHTML && data.total !== undefined) {
            this.itemsTarget.innerHTML = data.itemsHTML;
            this.totalTarget.innerText = `Total: ${data.total} €`;

            if (this.hasCartCountTarget && data.cartCount !== undefined) {
                this.cartCountTarget.innerText = data.cartCount;  // Mise à jour du nombre d'articles
            }

            document.querySelectorAll('.update-quantity-btn').forEach(button => {
                button.addEventListener('click', this.updateQuantity);  
            });
        } else {
            alert("Une erreur est survenue lors de la mise à jour du panier.");
        }
    }
}
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
            console.log(response.data);  // Vérifie la structure de la réponse ici
            if (response.data.success) {
                this.updateCart(response.data);  // Met à jour le panier avec les données reçues
            } else {
                console.error("Erreur côté serveur :", response.data);
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout au panier', error);
        }
    }
    
    updateCart(data) {
        if (data.success) {
            if (this.hasCartCountTarget && data.cartCount !== undefined) {
                console.log("Mise à jour du compteur du panier", data.cartCount);
                this.cartCountTarget.innerText = data.cartCount;  // Mise à jour du compteur
            }
    
            if (this.hasTotalTarget && data.total !== undefined) {
                console.log("Mise à jour du total", data.total);
                this.totalTarget.innerText = `${data.total} €`;
            }
    
            if (this.hasItemsTarget && data.itemsHTML) {
                console.log("Mise à jour des éléments du panier");
                this.itemsTarget.innerHTML = data.itemsHTML;
            }
        } else {
            console.error("Erreur lors de la mise à jour du panier.", data);
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

            if (this.hasItemsTarget && response.data.itemsHTML) {
                this.itemsTarget.innerHTML = response.data.itemsHTML;
            }

            if (this.hasTotalTarget && response.data.total !== undefined) {
                this.totalTarget.innerText = `${response.data.total} €`;
            }

            if (this.hasCartCountTarget && response.data.cartCount !== undefined) {
                this.cartCountTarget.innerText = response.data.cartCount;  // Mise à jour du nombre d'articles
            }

        } catch (error) {
            console.error("Erreur lors de la mise à jour de la quantité.", error);
        }
    }
}
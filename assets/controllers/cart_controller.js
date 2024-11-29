import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = ["total", "items", "cartCount", "searchInput", "searchResults"];

    connect() {
        console.log("Contrôleur cart connecté");
    }

    disconnect() {
        console.log("Contrôleur cart déconnecté");
    }

    // Recherche dynamique des produits
    async search(event) {
        const query = this.searchInputTarget.value.trim();

        if (query.length === 0) {
            this.searchResultsTarget.innerHTML = ""; // Efface les résultats si le champ est vide
            return;
        }

        const apiUrl = `${window.location.origin}/search/products?q=${encodeURIComponent(query)}`;

        try {
            const response = await axios.get(apiUrl);
            const products = response.data;

            // Génère les résultats sous forme de liste
            this.searchResultsTarget.innerHTML = products.length
                ? products
                      .map(
                          (product) =>
                              `<li data-product-id="${product.id}" class="search-result-item">${product.name}</li>`
                      )
                      .join("")
                : "<li>Aucun produit trouvé</li>";
        } catch (error) {
            console.error("Erreur lors de la recherche :", error);
            this.searchResultsTarget.innerHTML = "<li>Erreur lors de la recherche</li>";
        }
    }

    // Fonction pour ajouter un produit au panier
    async addToCart(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const apiUrl = window.location.origin + "/panier/ajouter";

        try {
            const response = await axios.post(apiUrl, formData);
            console.log(response.data); // Vérifie la structure de la réponse ici
            if (response.data.success) {
                this.updateCart(response.data); // Met à jour le panier avec les données reçues
            } else {
                console.error("Erreur côté serveur :", response.data);
            }
        } catch (error) {
            console.error("Erreur lors de l'ajout au panier", error);
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
            console.error("Erreur lors de la suppression du panier:", error);
            alert("Une erreur est survenue lors de la suppression du panier.");
        }
    }
}
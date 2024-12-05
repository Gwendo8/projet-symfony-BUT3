import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = ["total", "items", "cartCount", "searchInput", "searchResults"];

    connect() {
        console.log("Contrôleur cart connecté");
        console.log("test");
        console.log(this.hasCartCountTarget);
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
            console.log(response.data);  
            if (response.data.success) {
                console.log(response.data);
                this.updateCart(response.data); 
            } else {
                console.error("Erreur côté serveur :", response.data);
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout au panier', error);
        }
    }
    updateCart(data) {
                    console.log(data);
                    console.log("Cart count avant mise à jour : ", data.cartCount);
                    if (data.success) {
                        console.log("Cart count après mise à jour : ", data.cartCount);
                        console.log('hasCartCountTarget : ',this.hasCartCountTarget);
                        console.log(this.hasCartCountTarget && data.cartCount !== undefined);
                        // Mise à jour du compteur
                        if (this.hasCartCountTarget && data.cartCount !== undefined) {
                            console.log("Mise à jour du compteur de panier :", data.cartCount);
                            this.cartCountTarget.textContent = data.cartCount;
                        }

                        // Mise à jour du total
                        if (this.hasTotalTarget && data.total !== undefined) {
                            console.log("Mise à jour du total :", data.total);
                            this.totalTarget.textContent = `${data.total} €`;
                        }

                        // Mise à jour des items
                        if (this.hasItemsTarget && data.itemsHTML) {
                            console.log("Mise à jour des items du panier");
                            this.itemsTarget.innerHTML = data.itemsHTML;
            }
        } else {
            console.error("Erreur lors de la mise à jour du panier :", data);
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
                this.cartCountTarget.innerText = response.data.cartCount;  
            }

        } catch (error) {
            console.error("Erreur lors de la mise à jour de la quantité.", error);
        }
    }
     //Fonction pour la recherche
     async search(event) {
        const query = this.searchInputTarget.value.trim();

        if (query.length === 0) {
            this.searchResultsTarget.innerHTML = ""; 
            return;
        }

        const apiUrl = `${window.location.origin}/search/products?q=${encodeURIComponent(query)}`;

        try {
            const response = await axios.get(apiUrl);
            const products = response.data;

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
}
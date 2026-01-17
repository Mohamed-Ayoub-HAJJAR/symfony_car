/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

// Gallery Navigation
document.addEventListener("DOMContentLoaded", () => {
    const thumbnails = document.querySelectorAll(".thumbnail");
    const mainImage = document.getElementById("mainImage");
    const galleryPrevBtn = document.querySelector(".gallery-prev");
    const galleryNextBtn = document.querySelector(".gallery-next");
    let currentImageIndex = 0;

    // Initialize gallery
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener("click", () => {
            currentImageIndex = index;
            updateMainImage();
        });
    });

    // Previous button
    if (galleryPrevBtn) {
        galleryPrevBtn.addEventListener("click", () => {
            currentImageIndex =
                (currentImageIndex - 1 + thumbnails.length) % thumbnails.length;
            updateMainImage();
        });
    }

    // Next button
    if (galleryNextBtn) {
        galleryNextBtn.addEventListener("click", () => {
            currentImageIndex = (currentImageIndex + 1) % thumbnails.length;
            updateMainImage();
        });
    }

    function updateMainImage() {
        const thumbnail = thumbnails[currentImageIndex];
        const img = thumbnail.querySelector("img");

        // Update main image
        mainImage.src = img.src;
        mainImage.alt = img.alt;

        // Update active thumbnail
        thumbnails.forEach((thumb) => thumb.classList.remove("active"));
        thumbnail.classList.add("active");
    }

    const tabButtons = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const targetTab = this.getAttribute("data-tab");

            // Remove active class from all buttons and contents
            tabButtons.forEach((btn) => btn.classList.remove("active"));
            tabContents.forEach((content) =>
                content.classList.remove("active")
            );

            // Add active class to clicked button
            this.classList.add("active");

            // Show corresponding content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add("active");
            }
        });
    });

    // Favorite button
    const favoriteBtn = document.querySelector(".favorite-btn");
    if (favoriteBtn) {
        favoriteBtn.addEventListener("click", function () {
            this.classList.toggle("active");
        });
    }

    const brandSelect = document.querySelector("#car_filter_brand");
    const modelSelect = document.querySelector("#car_filter_model");
    const filterForm = document.querySelector('form[name="car_filter"]');
    const resultsContainer = document.getElementById("resultats-autos");
    brandSelect.addEventListener("change", function () {
        const brand = this.value;
        // Si aucune marque n'est sélectionnée, on vide les modèles
        if (!brand) {
            modelSelect.innerHTML =
                '<option value="">Tous les modèles</option>';
            return;
        }
        // Appel AJAX vers notre nouvelle route
        fetch("/api/models-by-brand/" + brand)
            .then((response) => response.json())
            .then((data) => {
                // Note : si votre contrôleur renvoie un objet avec une clé 'data',
                // il faut utiliser data.data. Sinon, utilisez juste l'argument reçu.
                modelSelect.innerHTML =
                    '<option value="">Tous les modèles</option>';
                data.models.forEach((item) => {
                    const option = document.createElement("option");
                    option.value = item.model;
                    option.textContent = item.model;
                    modelSelect.appendChild(option);
                });
                resultsContainer.innerHTML = data.html;
                resultsContainer.style.opacity = "1";
            });
    });

    function updateResults() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        console.log(params);
        resultsContainer.style.opacity = "0.5";

        // On appelle l'URL actuelle avec les paramètres de filtre
        fetch(window.location.pathname + "?" + params, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.text())
            .then((html) => {
                // On remplace directement le contenu car le serveur n'envoie que les cartes
                resultsContainer.innerHTML = html;
                resultsContainer.style.opacity = "1";
            })
            .catch((error) => {
                console.error("Erreur:", error);
                resultsContainer.style.opacity = "1";
            });
    }

    const allSelects = filterForm.querySelectorAll("select");
    allSelects.forEach((select) => {
        // Si ce n'est pas la marque (déjà gérée plus haut)
        if (select.id !== "car_filter_brand") {
            select.addEventListener("change", function () {
                console.log("Changement détecté sur : " + this.id);
                updateResults();
            });
        }
    });
});

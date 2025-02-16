function searchProducts() {
    let input = document.getElementById("search-input").value.toLowerCase();
    let products = document.querySelectorAll(".product-card");
    let categories = document.querySelectorAll(".product-category h2");

    let found = false; // Змінна для відстеження, чи знайдено хоча б один товар

    products.forEach(product => {
        let name = product.querySelector(".product-name").innerText.toLowerCase();
        let description = product.querySelector(".product-description").innerText.toLowerCase();

        if (name.includes(input) || description.includes(input)) {
            product.style.display = "block"; // Показати товар
            found = true; // Знайдено хоча б один товар
        } else {
            product.style.display = "none"; // Приховати товар
        }
    });

    // Приховуємо заголовки, якщо немає збігу в категорії
    categories.forEach(category => {
        let categoryContainer = category.parentElement;
        let visibleProducts = categoryContainer.querySelectorAll(".product-card[style='display: block;']");

        if (visibleProducts.length > 0) {
            category.style.display = "block"; // Показуємо заголовок, якщо є товари
        } else {
            category.style.display = "none"; // Приховуємо заголовок, якщо немає товарів
        }
    });
}


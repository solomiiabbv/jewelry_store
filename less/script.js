const cart = JSON.parse(localStorage.getItem('cart')) || [];
const cartItemsDiv = document.getElementById('cart-items');
const cartTotal = document.getElementById('cart-total');
const cartCount = document.getElementById('cart-count');



// Модальні вікна
const loginModal = document.getElementById('login-modal');
const registerModal = document.getElementById('register-modal');
const openRegisterBtn = document.querySelector('.modal-open-btn');
const closeRegisterBtn = document.getElementById('close-register-btn');
const closeLoginBtn = document.getElementById('close-btn');

openRegisterBtn.addEventListener('click', function(e) {
    e.preventDefault();
    registerModal.style.display = 'block';
});

closeRegisterBtn.addEventListener('click', function() {
    registerModal.style.display = 'none';
});

closeLoginBtn.addEventListener('click', function() {
    loginModal.style.display = 'none';
});

window.addEventListener('click', function(e) {
    if (e.target === loginModal) {
        loginModal.style.display = 'none';
    }
    if (e.target === registerModal) {
        registerModal.style.display = 'none';
    }
});

// Закриття модальних вікон по "Esc"
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        loginModal.style.display = 'none';
        registerModal.style.display = 'none';
    }
});

// Відкриття вікна входу після натискання "Уже маєте акаунт?"
document.getElementById("already-have-account").addEventListener("click", function (e) {
    e.preventDefault();
    registerModal.style.display = "none";
    loginModal.style.display = "block";
});

// Вхід користувача + анімація завантаження
document.querySelector("#login-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const loader = document.getElementById("loading-spinner");
    loader.style.display = "block"; 

    setTimeout(() => {
        loader.style.display = "none";
        alert("Вхід успішний!"); 
        loginModal.style.display = "none";
    }, 2000); // Імітація затримки 2 секунди
});

// Початкове оновлення кошика
updateCart();


document.addEventListener('DOMContentLoaded', function () {
    // Проверка наличия элементов модальных окон
    var registrationModal = document.getElementById("registrationModal");
    var loginModal = document.getElementById("loginModal");
    var registerModal = document.getElementById("registerModal");

    // Проверка наличия кнопок для открытия и закрытия модальных окон
    var openRegistrationModalBtn = document.getElementById("openRegistrationModalBtn");
    var openLoginModalBtn = document.getElementById("openLoginModalBtn");
    var openRegisterModalBtn = document.getElementById("openRegisterModalBtn");

    var closeRegistrationModalBtn = document.getElementById("closeRegistrationModalBtn");
    var closeLoginModalBtn = document.getElementById("closeLoginModalBtn");
    var closeRegisterModalBtn = document.getElementById("closeRegisterModalBtn");

    function openModal(modal) {
        if (modal) { // Проверка наличия модального окна
            modal.style.display = "flex";
        }
    }

    function closeModal(modal) {
        if (modal) { // Проверка наличия модального окна
            modal.style.display = "none";
        }
    }

    // Установка обработчиков событий только если элементы существуют
    if (openRegistrationModalBtn) {
        openRegistrationModalBtn.addEventListener('click', function () {
            openModal(registrationModal);
        });
    }

    if (openLoginModalBtn) {
        openLoginModalBtn.addEventListener('click', function () {
            openModal(loginModal);
        });
    }

    if (openRegisterModalBtn) {
        openRegisterModalBtn.addEventListener('click', function () {
            openModal(registerModal);
        });
    }

    if (closeRegistrationModalBtn) {
        closeRegistrationModalBtn.addEventListener('click', function () {
            closeModal(registrationModal);
        });
    }

    if (closeLoginModalBtn) {
        closeLoginModalBtn.addEventListener('click', function () {
            closeModal(loginModal);
        });
    }

    if (closeRegisterModalBtn) {
        closeRegisterModalBtn.addEventListener('click', function () {
            closeModal(registerModal);
        });
    }

    // Закрытие модальных окон при клике за пределами окон
    window.addEventListener('click', function (event) {
        if (event.target === registrationModal) {
            closeModal(registrationModal);
        } else if (event.target === loginModal) {
            closeModal(loginModal);
        } else if (event.target === registerModal) {
            closeModal(registerModal);
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('.review-carousel');
    const leftArrow = document.querySelector('.review-left-arrow');
    const rightArrow = document.querySelector('.review-right-arrow');
    let offset = 0;

    leftArrow.addEventListener('click', () => {
        offset = Math.min(offset + 350, 0);
        carousel.style.transform = `translateX(${offset}px)`;
    });

    rightArrow.addEventListener('click', () => {
        offset = Math.max(offset - 350, -carousel.scrollWidth + carousel.clientWidth);
        carousel.style.transform = `translateX(${offset}px)`;
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('.carousel');
    const leftArrow = document.querySelector('.left-arrow');
    const rightArrow = document.querySelector('.right-arrow');
    let offset = 0;

    leftArrow.addEventListener('click', () => {
        offset = Math.min(offset + 300, 0);
        carousel.style.transform = `translateX(${offset}px)`;
    });

    rightArrow.addEventListener('click', () => {
        offset = Math.max(offset - 300, -carousel.scrollWidth + carousel.clientWidth);
        carousel.style.transform = `translateX(${offset}px)`;
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetTab = tab.dataset.tab;

            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            tabContents.forEach(content => {
                if (content.id === targetTab) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        const parentSection = carousel.closest('section');
        const leftArrow = parentSection.querySelector('.left-arrow');
        const rightArrow = parentSection.querySelector('.right-arrow');
        let offset = 0;

        leftArrow.addEventListener('click', () => {
            offset = Math.min(offset + 300, 0);
            carousel.style.transform = `translateX(${offset}px)`;
        });

        rightArrow.addEventListener('click', () => {
            offset = Math.max(offset - 300, -carousel.scrollWidth + carousel.clientWidth);
            carousel.style.transform = `translateX(${offset}px)`;
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll('.tab');
    const modelCards = document.querySelectorAll('.model-card');

    const defaultCategory = 'Fashion';
    const defaultTab = document.querySelector(`.tab[data-category="${defaultCategory}"]`);
    defaultTab.classList.add('active');

    function showModelCards(category) {
        modelCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            if (cardCategory === category) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    showModelCards(defaultCategory);

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const category = tab.getAttribute('data-category');

            showModelCards(category);
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        const parentSection = carousel.closest('section');
        const leftArrow = parentSection.querySelector('.left-arrow');
        const rightArrow = parentSection.querySelector('.right-arrow');
        let offset = 0;

        leftArrow.addEventListener('click', () => {
            offset = Math.min(offset + 300, 0);
            carousel.style.transform = `translateX(${offset}px)`;
        });

        rightArrow.addEventListener('click', () => {
            offset = Math.max(offset - 300, -carousel.scrollWidth + carousel.clientWidth);
            carousel.style.transform = `translateX(${offset}px)`;
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        const parentSection = carousel.closest('section');
        const leftArrow = parentSection.querySelector('.left-arrow');
        const rightArrow = parentSection.querySelector('.right-arrow');
        let offset = 0;

        leftArrow.addEventListener('click', () => {
            offset = Math.min(offset + 300, 0);
            carousel.style.transform = `translateX(${offset}px)`;
        });

        rightArrow.addEventListener('click', () => {
            offset = Math.max(offset - 300, -carousel.scrollWidth + carousel.clientWidth);
            carousel.style.transform = `translateX(${offset}px)`;
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contactForm');

    contactForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Предотвращаем отправку формы по умолчанию

        // Получаем значения полей формы
        const name = document.getElementById('name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();

        // Проверяем каждое поле на валидность
        if (name === '' || phone === '' || email === '' || subject === '' || message === '') {
            alert('Пожалуйста, заполните все поля формы.');
            return; // Останавливаем отправку
        }

        //только буквы и пробелы
        const namePattern = /^[A-Za-zА-Яа-я\s]+$/;
        if (!namePattern.test(name)) {
            alert('Пожалуйста, введите корректное имя. Имя может содержать только буквы и пробелы.');
            return;
        }

        //только цифры и, возможно, дефисы или пробелы
const phonePattern = /^[\+\d\s\-]+$/;

if (!phonePattern.test(phone)) {
    alert('Пожалуйста, введите корректный номер телефона. Номер может содержать только цифры, пробелы, дефисы и символ "+".');
    return;
}

        //корректный формат email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            alert('Пожалуйста, введите корректный email.');
            return;
        }

        // только буквы, цифры и пробелы
        const subjectPattern = /^[A-Za-zА-Яа-я0-9\s]+$/;
        if (!subjectPattern.test(subject)) {
            alert('Пожалуйста, введите корректную тему сообщения. Тема может содержать только буквы, цифры и пробелы.');
            return;
        }

        // минимум 10 символов
        if (message.length < 10) {
            alert('Пожалуйста, введите сообщение длиной не менее 10 символов.');
            return;
        }

    });
});

document.addEventListener('DOMContentLoaded', function() {
    var loginModal = document.getElementById('loginModal');
    var openLoginModalBtn = document.getElementById('openLoginModalBtn');
    var closeLoginModalBtn = document.getElementById('closeLoginModalBtn');
    var loginForm = document.getElementById('loginForm');
    var loginError = document.getElementById('loginError');

    var registerModal = document.getElementById('registerModal');
    var openRegisterModalBtn = document.getElementById('openRegisterModalBtn');
    var closeRegisterModalBtn = document.getElementById('closeRegisterModalBtn');
    var registerForm = document.getElementById('registerForm');
    var registerError = document.getElementById('registerError');

    openLoginModalBtn.onclick = function() {
        loginModal.style.display = "block";
    }

    closeLoginModalBtn.onclick = function() {
        loginModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
        if (event.target == registerModal) {
            registerModal.style.display = "none";
        }
    }

    loginForm.onsubmit = function(event) {
        event.preventDefault();

        var formData = new FormData(loginForm);

        fetch('login.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    loginError.textContent = data.message;
                    loginError.style.display = 'block';
                }
            })
            .catch(error => {
                loginError.textContent = 'Ошибка авторизации: ' + error;
                loginError.style.display = 'block';
            });
    }

    openRegisterModalBtn.onclick = function() {
        registerModal.style.display = "block";
    }

    closeRegisterModalBtn.onclick = function() {
        registerModal.style.display = "none";
    }


    registerForm.onsubmit = function(event) {
        event.preventDefault();

        var formData = new FormData(registerForm);

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    registerError.textContent = data.message;
                    registerError.style.display = 'block';
                }
            })
            .catch(error => {
                registerError.textContent = 'Ошибка регистрации: ' + error;
                registerError.style.display = 'block';
            });
    }

    var openLoginModalFromRegister = document.getElementById('openLoginModalFromRegister');
    openLoginModalFromRegister.onclick = function() {
        registerModal.style.display = "none";
        loginModal.style.display = "block";
    }
});

document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('contact.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            document.querySelector('.contact-form').innerHTML = data;
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
});

function validateForm() {
    var phone = document.getElementById('phone_number').value;
    var name = document.getElementById('full_name').value;

    var phonePattern = /^\+?\d{1,3}[- ]?\d{3,}$/; // Паттерн для номера телефона
    var namePattern = /^[a-zA-Zа-яА-Я\s]+$/; // Паттерн для полного имени (только буквы и пробелы)

    var isValid = true;
    var phoneError = document.getElementById('phoneError');
    var nameError = document.getElementById('nameError');

    // Проверка номера телефона
    if (phone.trim() !== '' && !phonePattern.test(phone)) {
        phoneError.textContent = 'Неверный формат номера телефона';
        isValid = false;
    } else {
        phoneError.textContent = '';
    }

    // Проверка полного имени
    if (name.trim() !== '' && !namePattern.test(name)) {
        nameError.textContent = 'Имя должно содержать только буквы и пробелы';
        isValid = false;
    } else {
        nameError.textContent = '';
    }

    return isValid;
}

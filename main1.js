document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const username = document.getElementById('registerUsername').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        // Get user data from localStorage
        const usersString = localStorage.getItem('users');
        const users = usersString ? JSON.parse(usersString) : [];

        // Check if the username already exists
        if (users.find(u => u.username === username)) {
            alert('Username already exists.');
            return;
        }

        // Add the new user to the list
        users.push({ username, password });
        localStorage.setItem('users', JSON.stringify(users));

        alert('Registration successful!');
        // Redirect to login page or perform other actions
        console.log("Registration successful");
    });
});
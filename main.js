document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const username = document.getElementById('loginUsername').value;
        const password = document.getElementById('loginPassword').value;

        // Get user data from localStorage
        const usersString = localStorage.getItem('users');
        const users = usersString ? JSON.parse(usersString) : [];

        // Check if the user exists
        const user = users.find(u => u.username === username && u.password === password);

        if (user) {
            alert('Login successful!');
            // Redirect to the main page or perform other actions
            console.log("Login successful");
        } else {
            alert('Incorrect username or password.');
        }
    });
});
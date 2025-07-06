

// User deletion functionality

function confirmUserDeletion(form) {
    return confirm("Are you sure you want to delete this user?");
}



//logut popup -------------------------------------------------------

document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            // Show a browser-style confirmation dialog
            if (confirm('Are you sure you want to logout?')) {
                // Redirect to the logout page or perform logout actions
                window.location.href = '../home/logout.php'; // Replace with your logout URL
            }
        });
    }
});

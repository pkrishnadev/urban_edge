

//header--------------------------------------

document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const closeMenu = document.getElementById('closeMenu');
    const pageContent = document.body;

    
    // Sidebar functionality: Open sidebar on menu button click
    menuToggle.addEventListener('click', function () {
        sidebar.style.width = '250px'; // Open the sidebar
    });

    window.onload = function () {
        sidebar.style.width = '0'; // Start with sidebar hidden
    };
    
    // Close sidebar when the close button is clicked
    closeMenu.addEventListener('click', function () {
        sidebar.style.width = '0'; // Close the sidebar
    });

    // Close sidebar when clicking outside of the sidebar
    pageContent.addEventListener('click', function (event) {
        // Check if the click is outside the sidebar and menuToggle button
        if (event.target !== sidebar && !sidebar.contains(event.target) && event.target !== menuToggle) {
            sidebar.style.width = '0'; // Close the sidebar
        }
    });

    // Prevent the sidebar from closing when clicking inside
    sidebar.addEventListener('click', function (event) {
        event.stopPropagation(); // Prevent closing when inside the sidebar
    });

});








// Register JavaScript ---------------------------------
const registerButton = document.getElementById('registerButton');
const emailInput = document.getElementById('email');
const emailError = document.getElementById('emailError');
const passwordInput = document.getElementById('password');
const passwordError = document.getElementById('passwordError');

// Live email validation
emailInput.addEventListener('input', function () {
    const email = emailInput.value.trim();
    if (email.length > 0) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'register.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText.trim() === 'exists') {
                    emailError.textContent = 'Email already exists';
                } else {
                    emailError.textContent = '';
                }
                validateForm();
            }
        };
        xhr.send('validate_email=true&email=' + encodeURIComponent(email));
    } else {
        emailError.textContent = 'Email is required';
        validateForm();
    }
});

// Live password validation
passwordInput.addEventListener('input', function () {
    const password = passwordInput.value.trim();
    if (password.length === 0) {
        passwordError.textContent = 'Password is required';
    } else if (password.length < 6) {
        passwordError.textContent = 'Password must be at least 6 characters long';
    } else {
        passwordError.textContent = '';
    }
    validateForm();
});

// Validate the form before enabling the Register button
function validateForm() {
    const isEmailValid = emailError.textContent === '' && emailInput.value.trim().length > 0;
    const isPasswordValid = passwordError.textContent === '' && passwordInput.value.trim().length >= 6;
    registerButton.disabled = !(isEmailValid && isPasswordValid);
}

// Initial validation on page load
document.addEventListener('DOMContentLoaded', validateForm);










//  Confirmation before removing address---------------------------------------------------------
document.querySelectorAll('.remove-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        if (!confirm("Are you sure you want to remove this address?")) {
            event.preventDefault(); // Prevent form submission
        }
    });
});


//add address-----------------

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const addressNameInput = document.getElementById("address_name");
    const addressLine1Input = document.getElementById("address_line1");
    const cityInput = document.getElementById("city");
    const districtInput = document.getElementById("district");
    const pincodeInput = document.getElementById("pincode");
    const phoneNumberInput = document.getElementById("phone_number");

    form.addEventListener("submit", function (event) {
        // Simple check for empty fields (you can expand this with more validations)
        if (
            addressNameInput.value.trim() === "" ||
            addressLine1Input.value.trim() === "" ||
            cityInput.value.trim() === "" ||
            districtInput.value.trim() === "" ||
            pincodeInput.value.trim() === "" ||
            phoneNumberInput.value.trim() === ""
        ) {
            alert("Please fill out all required fields.");
            event.preventDefault(); // Stop form submission
        }
    });
});


// order histoty------------------------------------------
document.querySelectorAll('.cancel-order').forEach(button => {
    button.addEventListener('click', function () {
        const orderId = this.getAttribute('data-id');
        const confirmation = confirm("Are you sure you want to cancel this order?");
        
        if (confirmation) {
            // AJAX request to cancel the order
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'cancel_order.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Order cancelled successfully!');
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    alert('Error cancelling order. Please try again.');
                }
            };
            xhr.send('order_id=' + orderId);
        }
    });
});

// Handle View Order Button Click
document.querySelectorAll('.view-order').forEach(button => {
    button.addEventListener('click', function () {
        const orderId = this.getAttribute('data-id');
        window.location.href = 'view_user_order.php?order_id=' + orderId;
    });
});




// JavaScript for category buttons
function exploreCategory(category) {
    // Redirect to the category page or display items based on category selection
    window.location.href = category + "_tshirts.php";
}






// Products Details -------------------------------------------------------

function validateSizeSelection(formId) {
    const sizeDropdown = document.getElementById('size-dropdown');
    const selectedSize = sizeDropdown.value;

    if (!selectedSize) {
        alert('Please select a size before proceeding.');
        return false; // Prevent form submission
    }

    if (formId === 'cartForm') {
        document.getElementById('cartSelectedSize').value = selectedSize;
    } else if (formId === 'buyNowForm') {
        document.getElementById('buyNowSelectedSize').value = selectedSize;
    }

    return true; // Allow form submission
}


function changeImage(image) {
    document.getElementById('mainImage').src = image;
}

// add to cart  --------------------------------------------------------------



// Add to cart using AJAX
function addToCart(event) {
    event.preventDefault(); 
    const form = document.getElementById('cartForm');
    const formData = new FormData(form);

 
    const size = formData.get('size');
    if (!size) {
        alert('Please select a size.');
        return;
    }


    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); 
        })
        .then(data => {
            if (data.status === 'success') {
                alert(data.message); // Show success message as a popup

                
                updateCartDetails(data.cart_count, data.total_amount);

                
                setTimeout(() => {
                    location.reload();
                }, 0); //
            } else {
                alert(data.message); // Show error message as a popup
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            alert('An error occurred. Please try again.'); // Show general error as a popup
        });
}

// Utility function to update cart details dynamically
function updateCartDetails(cartCount, totalAmount) {
    const cartCountElement = document.getElementById('cart-count');
    const totalAmountElement = document.getElementById('total-amount');

    if (cartCountElement) {
        cartCountElement.textContent = cartCount || 0; // Update cart count
    }

    if (totalAmountElement) {
        totalAmountElement.textContent = `₹${(totalAmount || 0).toFixed(2)}`; // Update total amount
    }
}




//cart ----------------------------------------------------------------------------------------


function updateCart(event, key) {
    event.preventDefault();

    // Get the quantity value from the select dropdown
    const quantitySelect = document.getElementById(`quantity-${key}`);
    const quantity = parseInt(quantitySelect.value);

    // Prepare form data
    const formData = new FormData();
    formData.append('key', key);
    formData.append('quantity', quantity);
    formData.append('update_quantity', true);

    // Send data to the server using fetch
    fetch('cart.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update the item's total price
                const itemTotal = document.getElementById(`item-total-${key}`);
                if (itemTotal) {
                    itemTotal.textContent = `₹${data.item_total.toFixed(2)}`;
                }

                // Update the overall total price
                const totalAmount = document.getElementById('total-amount');
                if (totalAmount) {
                    totalAmount.textContent = `₹${data.total_amount.toFixed(2)}`;
                }

                // Automatically refresh the page after updating
                setTimeout(() => {
                    location.reload();
                }, 0); 
            } else {
                console.error('Error updating cart:', data.message);
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
        });
}

// Initialize event listeners for cart form
window.onload = function () {
    // Add event listeners for quantity update dropdowns
    const quantityDropdowns = document.querySelectorAll('.quantity-input');
    quantityDropdowns.forEach(dropdown => {
        const key = dropdown.closest('form').querySelector('input[name="key"]').value;
        dropdown.addEventListener('change', (event) => updateCart(event, key));
    });
};

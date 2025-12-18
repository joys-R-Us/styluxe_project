// public/js/styluxe/app.js

document.addEventListener('DOMContentLoaded', function() {
    console.log('🎀 Styluxe app.js loaded!');
    
    // ===== NOTIFICATION BELL TOGGLE =====
    const notificationBell = document.getElementById('notificationBell');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationBell && notificationDropdown) {
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationDropdown.contains(e.target) && e.target !== notificationBell) {
                notificationDropdown.classList.remove('show');
            }
        });
    }

    // ===== AUTO-HIDE ALERTS =====
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ===== CONFIRM DELETE =====
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // ===== IMAGE PREVIEW ON FILE INPUT =====
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // ===== SEARCH ENHANCEMENT =====
    const searchInputs = document.querySelectorAll('input[name="search"]');
    searchInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    });

    // ===== FLOATING ANIMATION FOR SILHOUETTES =====
    const silhouettes = document.querySelectorAll('.silhouette, .pattern-img');
    silhouettes.forEach(silhouette => {
        const randomDelay = Math.random() * 5;
        const randomDuration = 5 + Math.random() * 5;
        silhouette.style.animationDelay = `${randomDelay}s`;
        silhouette.style.animationDuration = `${randomDuration}s`;
    });

    // ===== SMOOTH SCROLL FOR ANCHOR LINKS =====
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // ===== STOCK STATUS AUTO-UPDATE ON QUANTITY CHANGE =====
    const stockQuantityInputs = document.querySelectorAll('input[name="quantity"]');
    stockQuantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const qty = parseInt(this.value) || 0;
            const lowThresholdInput = document.querySelector('input[name="low_stock_threshold"]');
            const lowThreshold = lowThresholdInput ? parseInt(lowThresholdInput.value) || 10 : 10;
            const statusSelect = document.querySelector('select[name="status"]');

            if (statusSelect) {
                if (qty <= 0) {
                    statusSelect.value = 'Sold Out';
                } else if (qty <= lowThreshold) {
                    statusSelect.value = 'Out-Of-Stock';
                } else {
                    statusSelect.value = 'Available';
                }
            }
        });
    });

    // ===== NOTIFICATION POLLING (Check every 30 seconds) =====
    function pollNotifications() {
        const notificationRoute = document.querySelector('meta[name="notification-route"]');
        if (notificationRoute) {
            fetch(notificationRoute.content)
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(error => console.error('Error polling notifications:', error));
        }
    }

    // Poll every 30 seconds if user is authenticated
    if (document.querySelector('.notification-bell')) {
        setInterval(pollNotifications, 30000);
    }

    // ===== FORM VALIDATION ENHANCEMENT =====
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--danger)';
                    
                    // Remove error styling after user types
                    field.addEventListener('input', function() {
                        this.style.borderColor = '';
                    }, { once: true });
                }
            });

            if (!isValid && !e.submitter?.hasAttribute('formnovalidate')) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                
                // Focus first invalid field
                const firstInvalid = form.querySelector('[required]:invalid, [required][value=""]');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    });

    // ===== COPY TO CLIPBOARD =====
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = this.textContent;
                this.textContent = '✅ Copied!';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        });
    });

    // ===== LOADING SPINNER FOR ASYNC FORMS =====
    const asyncForms = document.querySelectorAll('form[method="POST"]');
    asyncForms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<span>⏳ Processing...</span>';
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 3000);
            }
        });
    });

    // ===== DYNAMIC PRICE CALCULATION FOR ORDERS =====
    const quantityInputs = document.querySelectorAll('input[data-price]');
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const price = parseFloat(this.getAttribute('data-price') || 0);
            const quantity = parseInt(this.value) || 0;
            const subtotalElement = this.closest('tr')?.querySelector('.subtotal');
            
            if (subtotalElement) {
                const subtotal = price * quantity;
                subtotalElement.textContent = '₱' + subtotal.toFixed(2);
                
                // Update total
                updateOrderTotal();
            }
        });
    });

    function updateOrderTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(subtotal => {
            const value = parseFloat(subtotal.textContent.replace('₱', '').replace(',', ''));
            if (!isNaN(value)) {
                total += value;
            }
        });
        
        const totalElement = document.getElementById('orderTotal');
        if (totalElement) {
            totalElement.textContent = '₱' + total.toFixed(2);
        }
    }

    // ===== INITIALIZE ON PAGE LOAD =====
    console.log('✅ All Styluxe scripts initialized successfully!');
});


// ===== HELPER FUNCTIONS =====

// Format currency
function formatCurrency(amount) {
    return '₱' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--info)'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        opacity: 1;
        transition: opacity 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Export for use in other scripts
window.StyluxeHelpers = {
    formatCurrency,
    debounce,
    showToast
};
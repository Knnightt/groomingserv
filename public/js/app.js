// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if(targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if(targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
            
            // Update URL hash without scrolling
            history.pushState(null, null, targetId);
        }
    });
});

// Form submission handling
document.addEventListener('DOMContentLoaded', function() {
    // Newsletter form
    const newsletterForm = document.querySelector('footer form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (this.checkValidity()) {
                // Show success message
                const button = this.querySelector('button');
                const originalHTML = button.innerHTML;
                
                button.innerHTML = '<i class="bi bi-check-circle"></i> Subscribed!';
                button.classList.remove('btn-warning');
                button.classList.add('btn-success');
                
                // Reset after 3 seconds
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-warning');
                    emailInput.value = '';
                }, 3000);
                
                // In a real application, you would send this to your server
                console.log('Subscribed email:', email);
            }
        });
    }
    
    // Pricing card animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    }, observerOptions);
    
    // Observe pricing cards
    document.querySelectorAll('.pricing-card').forEach(card => {
        observer.observe(card);
    });
});
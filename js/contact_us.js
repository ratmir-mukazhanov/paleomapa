document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            // Toggle active class on question
            this.classList.toggle('active');
            
            // Toggle show class on answer
            const answer = this.nextElementSibling;
            answer.classList.toggle('show');
        });
    });
    
    // Form validation
    const contactForm = document.getElementById('contactForm');
    
    contactForm.addEventListener('submit', function(e) {
        let valid = true;
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const subject = document.getElementById('subject');
        const message = document.getElementById('message');
        
        // Simple validation
        if (name.value.trim() === '') {
            valid = false;
            name.style.borderColor = 'red';
        } else {
            name.style.borderColor = '#ddd';
        }
        
        if (email.value.trim() === '' || !validateEmail(email.value)) {
            valid = false;
            email.style.borderColor = 'red';
        } else {
            email.style.borderColor = '#ddd';
        }
        
        if (subject.value.trim() === '') {
            valid = false;
            subject.style.borderColor = 'red';
        } else {
            subject.style.borderColor = '#ddd';
        }
        
        if (message.value.trim() === '') {
            valid = false;
            message.style.borderColor = 'red';
        } else {
            message.style.borderColor = '#ddd';
        }
        
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly.');
        }
    });
    
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
});


// Animation on scroll
const animateOnScroll = () => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const element = entry.target;
        const delay = element.dataset.delay || 0;
        
        // Add initialized class first
        element.classList.add('initialized');
        
        // Add animation class after the specified delay
        setTimeout(() => {
          element.classList.add(element.dataset.animation);
          element.classList.add('show');
          
          // If this is a montessori card, add extra animation
          if (element.classList.contains('montessori-card')) {
            element.style.transitionDelay = `${delay}ms`;
          }
        }, parseInt(delay));
      }
    });
  }, {
    threshold: 0.2, // Increased threshold for smoother trigger
    rootMargin: '50px'
  });

  // Observe all animated elements
  document.querySelectorAll('.animate').forEach(element => {
    observer.observe(element);
  });
};

// Initialize animations when document is ready
document.addEventListener('DOMContentLoaded', () => {
  // Animate hero text immediately
  const heroElements = document.querySelectorAll('.hero-text .animate');
  heroElements.forEach((element, index) => {
    element.classList.add('initialized');
    setTimeout(() => {
      element.classList.add(element.dataset.animation);
      element.classList.add('show');
    }, index * 200); // Add delay between each element
  });

  // Initialize scroll animations
  animateOnScroll();
});

// Observe divider elements
const observerDividers = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // Add a small delay for each divider
      setTimeout(() => {
        entry.target.classList.add('show');
      }, 200);
    }
  });
}, {
  threshold: 0.2,  // Giảm threshold để trigger sớm hơn
  rootMargin: '0px'
});

// Observe vertical and horizontal dividers
const dividers = document.querySelectorAll('.montessori-divider-vertical, .montessori-divider-horizontal');
dividers.forEach(divider => {
  observerDividers.observe(divider);
});

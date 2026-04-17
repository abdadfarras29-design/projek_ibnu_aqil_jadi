// ===========================
// DATA CONFIGURATION
// ===========================

// Data statistik sekolah (dapat diubah sesuai kebutuhan)
const schoolData = {
    students: 1250,
    teachers: 75,
    achievements: 150,
    yearEstablished: 30
};

// ===========================
// NAVIGATION FUNCTIONS
// ===========================

/**
 * Toggle mobile menu visibility
 */
function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
}

/**
 * Show specific section and hide others
 * @param {string} sectionId - ID of the section to display
 */
function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.classList.add('active');
    }

    // Close mobile menu if open
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.remove('active');

    // Scroll to top smoothly
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===========================
// ANIMATION FUNCTIONS
// ===========================

/**
 * Animate counter from 0 to target value
 * @param {HTMLElement} element - Element to animate
 * @param {number} target - Target number
 * @param {number} duration - Animation duration in milliseconds
 */
function animateCounter(element, target, duration = 2000) {
    let current = 0;
    const increment = target / (duration / 16); // 60fps

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

/**
 * Initialize all counter animations
 */
function initializeCounters() {
    animateCounter(document.getElementById('studentCount'), schoolData.students);
    animateCounter(document.getElementById('teacherCount'), schoolData.teachers);
    animateCounter(document.getElementById('achievementCount'), schoolData.achievements);
    animateCounter(document.getElementById('yearCount'), schoolData.yearEstablished);
}

// ===========================
// EVENT LISTENERS
// ===========================

/**
 * Initialize when page loads
 */
window.addEventListener('load', () => {
    initializeCounters();
});

/**
 * Close mobile menu when clicking outside
 */
document.addEventListener('click', (e) => {
    const navMenu = document.getElementById('navMenu');
    const hamburger = document.querySelector('.hamburger');

    // Check if click is outside menu and hamburger
    if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
        navMenu.classList.remove('active');
    }
});

/**
 * Handle navigation menu clicks & dropdown toggle
 */
document.addEventListener('DOMContentLoaded', () => {
    // Tutup menu mobile saat link diklik
    const navLinks = document.querySelectorAll('.nav-menu > li > a:not(.dropbtn)');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('navMenu').classList.remove('active');
        });
    });

    // ===========================
    // DROPDOWN CLICK TOGGLE
    // ===========================
    const dropbtns = document.querySelectorAll('.dropbtn');

    dropbtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation(); // ← PENTING: cegah event naik ke document

            const dropdown = btn.parentElement;
            const menu = dropdown.querySelector('.dropdown-content');
            const isOpen = menu.classList.contains('open');

            // Tutup semua dropdown yang sedang terbuka
            document.querySelectorAll('.dropdown-content.open').forEach(d => d.classList.remove('open'));

            // Buka/tutup dropdown yang diklik
            if (!isOpen) {
                menu.classList.add('open');
            }
        });
    });

    // Klik di mana saja di halaman → tutup semua dropdown
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content.open').forEach(d => d.classList.remove('open'));
        }
    });

    // Klik pada item dropdown → biarkan navigasi berjalan (jangan tutup sebelum pindah halaman)
    document.querySelectorAll('.dropdown-content a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.stopPropagation(); // jangan tutup dropdown sebelum navigasi terjadi
        });
    });
});

/**
 * Add scroll effect to navbar
 */
let lastScroll = 0;
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    const currentScroll = window.pageYOffset;

    if (currentScroll > 100) {
        navbar.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.15)';
    } else {
        navbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    }

    lastScroll = currentScroll;
});

// ===========================
// UTILITY FUNCTIONS
// ===========================

/**
 * Smooth scroll to element
 * @param {string} elementId - ID of element to scroll to
 */
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Add loading animation (optional)
 */
function showLoading() {
    // Implement loading animation if needed
    console.log('Loading...');
}

/**
 * Hide loading animation (optional)
 */
function hideLoading() {
    // Implement hide loading if needed
    console.log('Loading complete');
}

// ===========================
// CONTACT FORM HANDLER (OPTIONAL)
// ===========================

/**
 * Handle contact form submission
 * Uncomment and customize if you add a contact form
 */
/*
function handleContactForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    console.log('Form submitted:', data);
    
    // Add your form submission logic here
    // For example: send to backend API or email service
    
    alert('Terima kasih! Pesan Anda telah dikirim.');
    event.target.reset();
}
*/

// ===========================
// ADDITIONAL FEATURES
// ===========================

/**
 * Update school data dynamically
 * @param {Object} newData - New school data object
 */
function updateSchoolData(newData) {
    Object.assign(schoolData, newData);
    initializeCounters();
}

/**
 * Log current section for analytics (optional)
 */
function logSectionView(sectionId) {
    console.log(`User viewed section: ${sectionId}`);
    // Add analytics tracking here if needed
}

// ===========================
// EXPORT FUNCTIONS (if using modules)
// ===========================

// Uncomment if you're using ES6 modules
/*
export {
    toggleMenu,
    showSection,
    animateCounter,
    updateSchoolData,
    scrollToElement
};
*/

import './bootstrap';

// Function to add or remove classes based on scroll position
function checkScroll() {
    const logoElement = document.getElementById('hero_logo');
    const bodyElement = document.getElementById('main_container');
    const navElement = document.getElementById('hero_banner_nav');

    // If any of the elements are missing, probably best to just skip this animation
    if (logoElement && bodyElement && navElement) {
        if (window.scrollY > 200) {
            navElement.classList.add('z-[9]', '!h-[72px]', 'fixed', 'top-0', 'left-0', 'right-0', '!flex-row', 'flex-row', 'justify-start');
            navElement.classList.remove('flex-col', 'justify-center');
            bodyElement.classList.add('pt-[238px]');
            logoElement.classList.add('h-[48px]', 'w-[48px]');
            logoElement.classList.remove('h-[150px]', 'w-[150px]');
        } else {
            navElement.classList.add('flex-col', 'justify-center');
            navElement.classList.remove('z-[9]', '!h-[72px]', 'fixed', 'top-0', 'left-0', 'right-0', '!flex-row', 'flex-row', 'justify-start');
            bodyElement.classList.remove('pt-[238px]');
            logoElement.classList.add('h-[150px]', 'w-[150px]');
            logoElement.classList.remove('h-[48px]', 'w-[48px]');
        }
    }
}

// Adding scroll event listener to window
window.addEventListener('scroll', checkScroll);

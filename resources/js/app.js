import './bootstrap';

const sidebar = document.querySelector('[data-sidebar]');
const openButton = document.querySelector('[data-sidebar-toggle]');
const closeButton = document.querySelector('[data-sidebar-close]');

if (sidebar && openButton && closeButton) {
    const root = document.documentElement;
    const closeSidebar = () => root.classList.remove('sidebar-open');
    const openSidebar = () => root.classList.add('sidebar-open');

    openButton.addEventListener('click', openSidebar);
    closeButton.addEventListener('click', closeSidebar);
    window.addEventListener('resize', () => {
        if (window.innerWidth > 920) {
            closeSidebar();
        }
    });
}

const densityToggle = document.querySelector('[data-density-toggle]');
if (densityToggle) {
    const root = document.documentElement;
    densityToggle.addEventListener('click', () => {
        const isCompact = root.classList.toggle('compact-mode');
        densityToggle.textContent = isCompact ? 'Mode confortable' : 'Mode compact';
    });
}

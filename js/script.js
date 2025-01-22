document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.querySelector('.cities-wrapper');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');
    const scrollAmount = 300; // Width of one card

    if (scrollLeftBtn && scrollRightBtn && scrollContainer) {
        // Initially hide left scroll button
        scrollLeftBtn.style.display = 'none';

        // Function to update button visibility
        function updateScrollButtons() {
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            const currentScroll = Math.abs(parseInt(scrollContainer.style.transform?.split('translateX(')[1]) || 0);
            
            scrollLeftBtn.style.display = currentScroll <= 0 ? 'none' : 'flex';
            scrollRightBtn.style.display = currentScroll >= maxScroll ? 'none' : 'flex';
        }

        // Scroll left
        scrollLeftBtn.addEventListener('click', () => {
            const currentScroll = parseInt(scrollContainer.style.transform?.split('translateX(')[1]) || 0;
            const newScroll = Math.min(0, currentScroll + scrollAmount);
            scrollContainer.style.transform = `translateX(${newScroll}px)`;
            updateScrollButtons();
        });

        // Scroll right
        scrollRightBtn.addEventListener('click', () => {
            const currentScroll = parseInt(scrollContainer.style.transform?.split('translateX(')[1]) || 0;
            const maxScroll = -(scrollContainer.scrollWidth - scrollContainer.clientWidth);
            const newScroll = Math.max(maxScroll, currentScroll - scrollAmount);
            scrollContainer.style.transform = `translateX(${newScroll}px)`;
            updateScrollButtons();
        });

        // Update buttons on window resize
        window.addEventListener('resize', updateScrollButtons);

        // Initial button update
        updateScrollButtons();
    }
});

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('.dropdown-toggle') || event.target.closest('#' + dropdownId);
            if (!isClickInside && dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    }

    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // Dark mode functionality
    function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    }

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'true' || 
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }

    // Search and filter functionality
    function filterDevices() {
        const searchTerm = deviceSearch.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#deviceTableBody tr');

        rows.forEach(row => {
            if (row.cells.length > 3) {
                const deviceName = row.cells[1].textContent.toLowerCase().trim();
                const deviceStatus = row.querySelector('td:nth-child(4) span').textContent.toLowerCase().trim();
                
                const matchesSearch = deviceName.includes(searchTerm);
                const matchesStatus = statusTerm === '' || deviceStatus === statusTerm;
                
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            }
        });
    }

    // Add event listeners when document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const deviceSearch = document.getElementById('deviceSearch');
        const statusFilter = document.getElementById('statusFilter');

        if (deviceSearch && statusFilter) {
            deviceSearch.addEventListener('input', filterDevices);
            statusFilter.addEventListener('change', filterDevices);
        }
    });
</script>
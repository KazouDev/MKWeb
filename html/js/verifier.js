$(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'right',
        startDate: "05/13/2024",
    }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    const dropdownBtn = document.getElementById('dropdown-btn');
    const dropdownContent = document.getElementById('departments-dropdown');
    const communesDropdown = document.getElementById('communes-dropdown');
    const drops = document.querySelector('.drops');

    function toggleDropdownContent() {
        if (dropdownContent.style.display === 'none' || dropdownContent.style.display === '') {
            dropdownContent.style.display = 'inline-flex';
            communesDropdown.style.display = 'none'; // Fermer le menu des communes
            document.addEventListener('click', closeMenuOnClickOutside);
        } else {
            dropdownContent.style.display = 'none';
            communesDropdown.style.display = 'none'; // Fermer le menu des communes
            document.removeEventListener('click', closeMenuOnClickOutside);
        }
    }

    function closeMenuOnClickOutside(event) {
        if (!drops.contains(event.target) && event.target !== dropdownBtn) {
            dropdownContent.style.display = 'none';
            communesDropdown.style.display = 'none'; // Fermer le menu des communes
            document.removeEventListener('click', closeMenuOnClickOutside);
        }
    }

    dropdownBtn.addEventListener('click', toggleDropdownContent);

    const departmentCheckboxes = document.querySelectorAll('input[name="department"]');

    function handleDepartmentSelection() {
        departmentCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                communesDropdown.style.display = this.checked ? 'inline-block' : 'none';
            });
        });
    }

    handleDepartmentSelection();
});
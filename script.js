document.addEventListener('DOMContentLoaded', () => {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            button.classList.add('active');
            const targetTabId = button.dataset.tab;
            const targetTabContent = document.getElementById(targetTabId);
            if (targetTabContent) {
                targetTabContent.classList.add('active');
            }
        });
    });

    // Handle initial state based on URL hash (optional, for direct links to tabs)
    const initialHash = window.location.hash.substring(1);
    if (initialHash) {
        const initialTabButton = document.querySelector(`.tab-button[data-tab="${initialHash}"]`);
        if (initialTabButton) {
            initialTabButton.click();
        }
    } else {
        document.querySelector('.tab-button[data-tab="study-halls"]').click();
    }

    // Hero section buttons to switch tabs and scroll
    const bookStudyHallBtn = document.querySelector('.hero-buttons .btn-green');
    const bookSportsFacilityBtn = document.querySelector('.hero-buttons .btn-blue');

    if (bookStudyHallBtn) {
        bookStudyHallBtn.addEventListener('click', () => {
            document.querySelector('.tab-button[data-tab="study-halls"]').click();
            document.querySelector('.facilities-section').scrollIntoView({ behavior: 'smooth' });
        });
    }

    if (bookSportsFacilityBtn) {
        bookSportsFacilityBtn.addEventListener('click', () => {
            document.querySelector('.tab-button[data-tab="sports-facilities"]').click();
            document.querySelector('.facilities-section').scrollIntoView({ behavior: 'smooth' });
        });
    }

    // --- Modal Functionality ---
    const bookingModal = document.getElementById('bookingModal');
    const closeButton = document.querySelector('.close-button');
    const bookButtons = document.querySelectorAll('.book-button'); // Use a specific class for booking buttons
    const modalTitle = document.getElementById('modalTitle');
    const bookingDateInput = document.getElementById('bookingDate');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const confirmBookingBtn = document.querySelector('.confirm-booking-btn');
    const bookingForm = document.getElementById('bookingForm');


    // Function to set today's date in YYYY-MM-DD format for date input
    // This is the required format for input type="date"
    function setTodayDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(today.getDate()).padStart(2, '0');
        bookingDateInput.value = `${year}-${month}-${day}`;
    }

    // Open modal when any "Book Now" button is clicked
    bookButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const facilityName = event.target.dataset.facilityName;
            modalTitle.textContent = `Book ${facilityName}`;

            // Reset form fields and set current date
            bookingForm.reset(); // Clears all form inputs
            setTodayDate(); // Sets the date to today
            startTimeInput.value = ''; // Explicitly clear time inputs
            endTimeInput.value = '';

            bookingModal.classList.add('show');
        });
    });

    // Close modal when 'x' is clicked
    closeButton.addEventListener('click', () => {
        bookingModal.classList.remove('show');
        bookingForm.reset(); // Clear form on close
    });

    // Close modal when clicking outside the modal content
    window.addEventListener('click', (event) => {
        if (event.target === bookingModal) {
            bookingModal.classList.remove('show');
            bookingForm.reset(); // Clear form on close
        }
    });

    // Handle form submission (Confirm Booking button)
    confirmBookingBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default form submission

        const studentID = document.getElementById('studentID').value;
        const date = document.getElementById('bookingDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const purpose = document.getElementById('purpose').value;
        const facilityName = modalTitle.textContent.replace('Book ', ''); // Get facility name from modal title

        if (!studentID || !date || !startTime || !endTime || !purpose) {
            alert('Please fill in all required fields.');
            return;
        }

        // Basic time validation
        if (startTime && endTime && startTime >= endTime) {
            alert('End time must be after start time.');
            return;
        }

        // In a real application, you would send this data to a backend server.
        // For example, using fetch API:
        fetch('/api/book-facility', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                facility: facilityName,
                studentId: studentID,
                date: date,
                startTime: startTime,
                endTime: endTime,
                purpose: purpose
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking Confirmed! Your reference: ' + data.bookingRef);
                bookingModal.classList.remove('show');
                bookingForm.reset();
            } else {
                alert('Booking failed: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('An error occurred during booking. Please try again.');
        });
                bookingDateInput.value = `${year}-${month}-${day}`;              
                 modalTitle.textContent = `Book ${facilityName}`;

        // For now, an alert as a placeholder for successful booking:
        alert(`Booking Confirmed for ${facilityName}!
        Student ID: ${studentID}
        Date: ${date}
        Time: ${startTime} - ${endTime}
        Purpose: ${purpose}
        (This data would typically be sent to a server.)`);

        bookingModal.classList.remove('show');
        bookingForm.reset(); // Clear form after successful "booking"
    });
});

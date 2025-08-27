// public/js/index.js

document.addEventListener('DOMContentLoaded', () => {

	// =========================
	// Utilities
	// =========================
	function showToast(message) {
		const toast = document.createElement('div');
		toast.textContent = message;
		toast.style.cssText = `
      position: fixed;
      bottom: 1rem;
      right: 1rem;
      background: #333;
      color: #fff;
      padding: 0.75rem;
      border-radius: 4px;
      z-index: 9999;
    `;
		document.body.appendChild(toast);
		setTimeout(() => toast.remove(), 3000);
	}

	// =========================
	// Hide Past Bookings (with persistence)
	// =========================
	;(function initHidePastBookings() {
		const hidePastCheckbox = document.getElementById('hidePastCheckbox');
		const STORAGE_KEY = 'hidePastBookings';

		function applyHidePast(hide) {
			document
				.querySelectorAll('#bookingsTable tr[data-past="1"]')
				.forEach(row => {
					row.style.display = hide ? 'none' : '';
				});
		}

		function loadAndApply() {
			const saved = localStorage.getItem(STORAGE_KEY) === 'true';
			if (hidePastCheckbox) hidePastCheckbox.checked = saved;
			applyHidePast(saved);
		}

		if (hidePastCheckbox) {
			loadAndApply();
			hidePastCheckbox.addEventListener('change', () => {
				const hide = hidePastCheckbox.checked;
				localStorage.setItem(STORAGE_KEY, hide);
				applyHidePast(hide);
			});
		} else {
			loadAndApply();
		}
	})();

	// =========================
	// Homepage Tabs
	// =========================
	const studyHallsTab            = document.getElementById('studyHallsTab');
	const sportsFacilitiesTab      = document.getElementById('sportsFacilitiesTab');
	const studyHallsContent        = document.getElementById('studyHallsContent');
	const sportsFacilitiesContent  = document.getElementById('sportsFacilitiesContent');

	if (studyHallsTab && sportsFacilitiesTab && studyHallsContent && sportsFacilitiesContent) {
		function showSection(section) {
			studyHallsContent.classList.add('hidden');
			sportsFacilitiesContent.classList.add('hidden');
			studyHallsTab.classList.remove('tab-active');
			sportsFacilitiesTab.classList.remove('tab-active');

			if (section === 'studyHalls') {
				studyHallsContent.classList.remove('hidden');
				studyHallsTab.classList.add('tab-active');
			} else {
				sportsFacilitiesContent.classList.remove('hidden');
				sportsFacilitiesTab.classList.add('tab-active');
			}
		}

		studyHallsTab.addEventListener('click', () => showSection('studyHalls'));
		sportsFacilitiesTab.addEventListener('click', () => showSection('sportsFacilities'));
		showSection('studyHalls');
	}

	// =========================
	// Password Toggle
	// =========================
	const togglePassword = document.getElementById('togglePassword');
	const passwordInput  = document.getElementById('password');
	const eyeIcon        = document.getElementById('eyeIcon');

	if (togglePassword && passwordInput && eyeIcon) {
		togglePassword.addEventListener('click', () => {
			const isPwd = passwordInput.type === 'password';
			passwordInput.type = isPwd ? 'text' : 'password';
			eyeIcon.classList.toggle('eye-off-icon', !isPwd);
			eyeIcon.classList.toggle('eye-icon', isPwd);
		});
	}

	// =========================
	// Booking Modal Logic
	// =========================
	const bookingModal           = document.getElementById('bookingModal');
	const facilityFieldContainer = document.getElementById('facilityFieldContainer');
	const closeModalBtn          = document.getElementById('closeModal');

	if (bookingModal && facilityFieldContainer && closeModalBtn) {
		const showModal = () => bookingModal.classList.remove('hidden');
		const hideModal = () => bookingModal.classList.add('hidden');

		function getHiddenInput() {
			let hidden = bookingModal.querySelector('#facilityId');
			if (!hidden) {
				hidden = document.createElement('input');
				hidden.type = 'hidden';
				hidden.name = 'facility_id';
				hidden.id = 'facilityId';
				const form = bookingModal.querySelector('form');
				if (form) {
					form.insertBefore(hidden, facilityFieldContainer);
					console.log('Created missing hidden input #facilityId');
				} else {
					console.error('No form found inside bookingModal — cannot insert hidden input');
				}
			}
			return hidden;
		}

		function setFacilityId(val) {
			const hidden = getHiddenInput();
			if (hidden) {
				hidden.value = val;
				console.log(`facility_id set to: ${val}`);
			}
		}

		document.querySelectorAll('.book-now-btn').forEach(btn => {
			btn.addEventListener('click', () => {
				const id   = btn.dataset.facilityId;
				const name = btn.dataset.facilityName;
				setFacilityId(id);
				facilityFieldContainer.innerHTML = `
          <label class="block text-sm font-medium text-gray-700">Facility</label>
          <input
            type="text"
            value="${name}"
            class="mt-1 block w-full border border-gray-300 rounded-lg p-2 bg-gray-100 text-gray-600 cursor-not-allowed"
            readonly
          />
        `;
				showModal();
			});
		});

		document.querySelectorAll('.create-booking').forEach(btn => {
			btn.addEventListener('click', () => {
				const facilities = window.availableFacilities || [];
				const opts = facilities.map(f => `<option value="${f.id}">${f.name}</option>`).join('');
				facilityFieldContainer.innerHTML = `
          <label class="block text-sm font-medium text-gray-700">Facility</label>
          <select
            id="facilitySelect"
            class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >${opts}</select>
        `;
				const select = document.getElementById('facilitySelect');
				if (facilities.length) {
					select.value = facilities[0].id;
					setFacilityId(select.value);
				}
				select.addEventListener('change', () => {
					setFacilityId(select.value);
				});
				showModal();
			});
		});

		closeModalBtn.addEventListener('click', hideModal);
		bookingModal.addEventListener('click', e => {
			if (e.target === bookingModal) hideModal();
		});
	} else {
		console.warn('Booking modal missing one of the required elements');
	}

	// =========================
	// Cancel Booking AJAX
	// =========================
	const bookingsTable = document.getElementById('bookingsTable');

	if (bookingsTable) {
		bookingsTable.addEventListener('click', e => {
			const btn = e.target.closest('.cancel-btn');
			if (!btn) return;
			const bookingId = btn.getAttribute('data-id');
			if (!bookingId) return;
			if (!confirm('Are you sure you want to cancel this booking?')) return;

			fetch('../core/cancel_booking.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: 'booking_id=' + encodeURIComponent(bookingId)
			})
				.then(res => res.json())
				.then(data => {
					if (data.success) {
						const row = btn.closest('tr');
						if (row) row.remove();
						showToast(data.message || 'Booking cancelled');
						location.reload();
					} else {
						showToast(data.error || data.message || 'Unable to cancel booking');
					}
				})
				.catch(err => {
					console.error('Cancel error:', err);
					showToast('Error contacting server');
				});
		});
	}

	// =========================
	// Popular Facilities Toggle
	// =========================
	;(function initPopularFacilitiesToggle() {
		const toggleBtn     = document.getElementById('toggleFacilitiesBtn');
		const topFacilities = document.getElementById('topFacilities');
		const allFacilities = document.getElementById('allFacilities');
		if (!toggleBtn || !topFacilities || !allFacilities) return;

		toggleBtn.addEventListener('click', () => {
			const showingAll = !allFacilities.classList.contains('hidden');
			if (showingAll) {
				allFacilities.classList.add('hidden');
				topFacilities.classList.remove('hidden');
				toggleBtn.textContent = 'View All';
			} else {
				topFacilities.classList.add('hidden');
				allFacilities.classList.remove('hidden');
				toggleBtn.textContent = 'Show Less';
			}
		});
	})();

	// =========================
	// Admin Edit Booking Modal
	// =========================
	(function initAdminEditBookingModal() {
		const editModal         = document.getElementById('editModal');
		const closeEditModalBtn = document.getElementById('closeEditModalBtn');
		const editBtns          = document.querySelectorAll('.booking-edit-btn');
		if (!editModal || !closeEditModalBtn || !editBtns.length) return;

		editBtns.forEach(btn => {
			btn.addEventListener('click', () => {
				document.getElementById('modalBookingId').value = btn.dataset.id;
				document.getElementById('modalDate').value      = btn.dataset.date;
				document.getElementById('modalStart').value     = btn.dataset.start;
				document.getElementById('modalEnd').value       = btn.dataset.end;
				document.getElementById('modalStatus').value    = btn.dataset.status;
				editModal.classList.remove('hidden');
			});
		});

		function closeEdit() {
			editModal.classList.add('hidden');
		}

		closeEditModalBtn.addEventListener('click', closeEdit);
		editModal.addEventListener('click', e => {
			if (e.target === editModal) closeEdit();
		});
	})();

	// =========================
	// Admin Bookings: ViewAll/ShowLess
	// =========================
	;(function initAdminBookingToggle() {
		const toggleBtn   = document.getElementById('toggleAdminBookingsBtn');
		const bookingRows = Array.from(document.querySelectorAll('.admin-booking-row'));
		if (!toggleBtn || !bookingRows.length) return;

		let showAll = false;
		function render() {
			bookingRows.forEach((row, idx) => {
				row.classList.toggle('hidden', !showAll && idx >= 3);
			});
			toggleBtn.textContent = showAll ? 'Show Less' : 'View All';
		}

		toggleBtn.addEventListener('click', () => {
			showAll = !showAll;
			render();
		});

		render();
	})();

	// =========================
	// Book Now (Homepage) Logic
	// =========================
	;(function initBookNowHomepage() {
		if (!window.NSBM) return;
		document.querySelectorAll('.book-now-homepage-btn').forEach(btn => {
			btn.addEventListener('click', () => {
				const fid = btn.dataset.facilityId;
				if (!window.NSBM.isStudent) {
					const redirectUrl = `${window.NSBM.loginUrl}` +
						`?redirect=booking&facility=${encodeURIComponent(fid)}`;
					console.log('Redirecting to (login):', redirectUrl);
					window.location.href = redirectUrl;
				} else {
					const userUrl = `${window.NSBM.userPage}` +
						`?modal=1&facility=${encodeURIComponent(fid)}`;
					console.log('Redirecting to (user page):', userUrl);
					window.location.href = userUrl;
				}
			});
		});
	})();

});

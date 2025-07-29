document.addEventListener('DOMContentLoaded', () => {
	// Logic for homepage tabs
	const studyHallsTab = document.getElementById('studyHallsTab');
	const sportsFacilitiesTab = document.getElementById('sportsFacilitiesTab');
	const studyHallsContent = document.getElementById('studyHallsContent');
	const sportsFacilitiesContent = document.getElementById('sportsFacilitiesContent');

	if (studyHallsTab && sportsFacilitiesTab && studyHallsContent && sportsFacilitiesContent) {
		const showSection = (sectionToShow) => {
			studyHallsContent.classList.add('hidden');
			sportsFacilitiesContent.classList.add('hidden');

			studyHallsTab.classList.remove('tab-active');
			sportsFacilitiesTab.classList.remove('tab-active');

			if (sectionToShow === 'studyHalls') {
				studyHallsContent.classList.remove('hidden');
				studyHallsTab.classList.add('tab-active');
			} else if (sectionToShow === 'sportsFacilities') {
				sportsFacilitiesContent.classList.remove('hidden');
				sportsFacilitiesTab.classList.add('tab-active');
			}
		};

		studyHallsTab.addEventListener('click', () => {
			showSection('studyHalls');
		});

		sportsFacilitiesTab.addEventListener('click', () => {
			showSection('sportsFacilities');
		});

		// Initialize with study halls shown
		showSection('studyHalls');
	}
});
document.addEventListener('DOMContentLoaded', function() {
	const togglePassword = document.getElementById('togglePassword');
	const passwordInput = document.getElementById('password');
	const eyeIcon = document.getElementById('eyeIcon');
	const loginForm = document.querySelector('form');

	if (togglePassword && passwordInput && eyeIcon) {
		togglePassword.addEventListener('click', function() {
			const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
			passwordInput.setAttribute('type', type);

			if (type === 'password') {
				eyeIcon.classList.remove('eye-off-icon');
				eyeIcon.classList.add('eye-icon');
			} else {
				eyeIcon.classList.remove('eye-icon');
				eyeIcon.classList.add('eye-off-icon');
			}
		});
	}
});

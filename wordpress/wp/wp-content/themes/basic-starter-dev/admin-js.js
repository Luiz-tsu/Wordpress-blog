document.addEventListener('DOMContentLoaded', function () {
	const sidebarLinks = document.querySelectorAll('.bsd-settings-sidebar a');
	const sections = document.querySelectorAll('.bsd-settings-content section');
	const storageKey = 'bsd_theme_settings_active_tab';

	if (!sidebarLinks.length || !sections.length) return;

	// Function to switch tabs
	function switchTab(targetID) {
		// Remove active class from all links
		sidebarLinks.forEach(l => l.classList.remove('active'));

		// Hide all sections
		sections.forEach(sec => sec.style.display = 'none');

		// Find and activate the target link
		const targetLink = document.querySelector(`.bsd-settings-sidebar a[href="#${targetID}"]`);
		if (targetLink) {
			targetLink.classList.add('active');
		}

		// Show the selected section
		const activeSection = document.getElementById(targetID);
		if (activeSection) {
			activeSection.style.display = 'block';
		}

		// Save to localStorage
		localStorage.setItem(storageKey, targetID);

		// Update hidden input field
		const activeTabInput = document.getElementById('bsd-active-tab-input');
		if (activeTabInput) {
			activeTabInput.value = targetID;
		}
	}

	// Add click event listeners to tabs
	sidebarLinks.forEach(link => {
		link.addEventListener('click', function (e) {
			e.preventDefault();

			// Get target section ID
			const targetID = this.getAttribute('href').replace('#', '');
			switchTab(targetID);
		});
	});

	// Function to restore active tab
	function restoreActiveTab() {
		// Check URL hash first (highest priority)
		const urlHash = window.location.hash.replace('#', '');
		if (urlHash && document.getElementById(urlHash)) {
			switchTab(urlHash);
			return;
		}

		// Check localStorage
		const savedTab = localStorage.getItem(storageKey);
		if (savedTab && document.getElementById(savedTab)) {
			switchTab(savedTab);
			return;
		}

		// Default to first section
		if (sections.length > 0) {
			const firstSection = sections[0];
			const firstSectionID = firstSection.getAttribute('id');
			if (firstSectionID) {
				switchTab(firstSectionID);
			} else {
				// Fallback: show first section
				sections.forEach((sec, index) => {
					sec.style.display = index === 0 ? 'block' : 'none';
				});
				if (sidebarLinks.length > 0) {
					sidebarLinks[0].classList.add('active');
				}
			}
		}
	}

	// Restore active tab on page load
	restoreActiveTab();

	// Save tab when form is submitted
	const settingsForm = document.querySelector('.bsd-settings-content form');
	if (settingsForm) {
		settingsForm.addEventListener('submit', function () {
			// Get current active tab
			const activeLink = document.querySelector('.bsd-settings-sidebar a.active');
			if (activeLink) {
				const activeTabID = activeLink.getAttribute('href').replace('#', '');
				localStorage.setItem(storageKey, activeTabID);
				
				// Update hidden input field
				const activeTabInput = document.getElementById('bsd-active-tab-input');
				if (activeTabInput) {
					activeTabInput.value = activeTabID;
				}
			}
		});
	}
});


jQuery(document).ready(function ($) {
	let logoFrame, faviconFrame;

	// ====================
	// Upload Header Logo
	// ====================
	$('.bsd-upload-logo').on('click', function (e) {
		e.preventDefault();

		if (logoFrame) {
			logoFrame.open();
			return;
		}

		logoFrame = wp.media({
			title: 'Select or Upload Logo',
			button: { text: 'Use this logo' },
			multiple: false
		});

		logoFrame.on('select', function () {
			const attachment = logoFrame.state().get('selection').first().toJSON();
			$('#bsd_header_logo').val(attachment.url);
			$('#bsd-logo-preview').attr('src', attachment.url).show();
			$('.bsd-remove-logo').show();
		});

		logoFrame.open();
	});

	$('.bsd-remove-logo').on('click', function (e) {
		e.preventDefault();
		$('#bsd_header_logo').val('');
		$('#bsd-logo-preview').hide();
		$(this).hide();
	});

	// ====================
	// Upload Favicon
	// ====================
	$('.bsd-upload-favicon').on('click', function (e) {
		e.preventDefault();

		if (faviconFrame) {
			faviconFrame.open();
			return;
		}

		faviconFrame = wp.media({
			title: 'Select or Upload Favicon',
			button: { text: 'Use this favicon' },
			multiple: false
		});

		faviconFrame.on('select', function () {
			const attachment = faviconFrame.state().get('selection').first().toJSON();
			$('#bsd_header_favicon').val(attachment.url);
			$('#bsd-favicon-preview').attr('src', attachment.url).show();
			$('.bsd-remove-favicon').show();
		});

		faviconFrame.open();
	});

	$('.bsd-remove-favicon').on('click', function (e) {
		e.preventDefault();
		$('#bsd_header_favicon').val('');
		$('#bsd-favicon-preview').hide();
		$(this).hide();
	});
});

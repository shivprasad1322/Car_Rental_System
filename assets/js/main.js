document.addEventListener('DOMContentLoaded', function () {
    // Auto close alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            try { bootstrap.Alert.getOrCreateInstance(a).close(); } catch(e){}
        });
    }, 5000);

    // Admin DataTables
    if (window.jQuery && jQuery.fn.DataTable) {
        jQuery('.datatable').DataTable();
    }

    const aboutTabs = document.querySelector('[data-about-tabs]');
    if (aboutTabs) {
        const tabs = aboutTabs.querySelectorAll('[data-about-tab]');
        const panels = aboutTabs.querySelectorAll('[data-about-panel]');
        tabs.forEach(tab => tab.addEventListener('click', function () {
            const selected = this.dataset.aboutTab;
            tabs.forEach(item => {
                const active = item === this;
                item.classList.toggle('is-active', active);
                item.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            panels.forEach(panel => {
                const active = panel.dataset.aboutPanel === selected;
                panel.hidden = !active;
                panel.classList.toggle('is-active', active);
            });
        }));
    }

    const tripPlanner = document.querySelector('[data-trip-planner]');
    if (tripPlanner) {
        const rate = tripPlanner.querySelector('[data-planner-rate]');
        const days = tripPlanner.querySelector('[data-planner-days]');
        const daysOutput = tripPlanner.querySelector('[data-planner-days-output]');
        const support = tripPlanner.querySelector('[data-planner-support]');
        const total = tripPlanner.querySelector('[data-planner-total]');
        const currency = new Intl.NumberFormat('en-IN');
        let currentEstimate = 4500;

        const pulseCard = () => {
            tripPlanner.classList.add('is-active');
            clearTimeout(tripPlanner._plannerTimer);
            tripPlanner._plannerTimer = setTimeout(() => tripPlanner.classList.remove('is-active'), 350);
        };

        const animateTotal = (targetValue) => {
            const startValue = currentEstimate;
            const startTime = performance.now();
            const duration = 320;

            const tick = (timestamp) => {
                const progress = Math.min((timestamp - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const value = Math.round(startValue + (targetValue - startValue) * eased);
                total.textContent = `रू${currency.format(value)}`;

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    currentEstimate = targetValue;
                }
            };

            requestAnimationFrame(tick);
        };

        const updateEstimate = () => {
            const dayCount = Number(days.value);
            const estimate = (Number(rate.value) * dayCount) + (support.checked ? 500 : 0);
            daysOutput.textContent = `${dayCount} ${dayCount === 1 ? 'day' : 'days'}`;
            animateTotal(estimate);
            pulseCard();
        };

        [rate, days, support].forEach(input => input.addEventListener('input', updateEstimate));
        tripPlanner.addEventListener('mouseenter', () => tripPlanner.classList.add('is-active'));
        tripPlanner.addEventListener('mouseleave', () => tripPlanner.classList.remove('is-active'));
        updateEstimate();
    }

    const contactForm = document.querySelector('[data-contact-form]');
    if (contactForm) {
        const topicButtons = contactForm.querySelectorAll('[data-topic]');
        const topicValue = contactForm.querySelector('[data-contact-topic-value]');
        const responseNote = contactForm.querySelector('[data-contact-response]');
        const message = contactForm.querySelector('#contact-message');
        const count = contactForm.querySelector('[data-contact-count]');
        const responseNotes = {
            'Booking help': 'Booking questions usually get a reply within 2 hours.',
            'Choosing a car': 'Tell us your route and we will suggest a great fit.',
            'Something else': 'General questions are answered within one business day.'
        };

        topicButtons.forEach(button => button.addEventListener('click', function () {
            topicButtons.forEach(item => item.classList.toggle('is-active', item === this));
            topicValue.value = this.dataset.topic;
            responseNote.textContent = responseNotes[this.dataset.topic];
        }));

        const updateMessageCount = () => { count.textContent = message.value.length; };
        message.addEventListener('input', updateMessageCount);
        updateMessageCount();
    }

    const listingResults = document.querySelector('[data-listing-results]');
    if (listingResults) {
        const grid = listingResults.querySelector('[data-listing-grid]');
        const cards = Array.from(listingResults.querySelectorAll('[data-listing-card]'));
        const quickFilters = listingResults.querySelectorAll('[data-quick-filter]');
        const resultCount = listingResults.querySelector('[data-result-count]');
        const noMatch = listingResults.querySelector('[data-listing-no-match]');
        const sort = listingResults.querySelector('[data-listing-sort]');
        const viewButtons = listingResults.querySelectorAll('[data-listing-view]');

        const applyQuickFilter = (filter) => {
            let visible = 0;
            cards.forEach(card => {
                const matches = filter === 'all' || card.dataset.fuel === filter;
                card.hidden = !matches;
                if (matches) visible += 1;
            });
            resultCount.textContent = visible;
            noMatch.hidden = visible !== 0;
        };

        quickFilters.forEach(button => button.addEventListener('click', function () {
            quickFilters.forEach(item => item.classList.toggle('is-active', item === this));
            applyQuickFilter(this.dataset.quickFilter);
        }));

        sort.addEventListener('change', function () {
            const sorted = [...cards].sort((a, b) => {
                if (this.value === 'price-low') return Number(a.dataset.price) - Number(b.dataset.price);
                if (this.value === 'price-high') return Number(b.dataset.price) - Number(a.dataset.price);
                if (this.value === 'name') return a.dataset.name.localeCompare(b.dataset.name);
                return cards.indexOf(a) - cards.indexOf(b);
            });
            sorted.forEach(card => grid.appendChild(card));
        });

        viewButtons.forEach(button => button.addEventListener('click', function () {
            const listView = this.dataset.listingView === 'list';
            viewButtons.forEach(item => item.classList.toggle('is-active', item === this));
            grid.classList.toggle('is-list', listView);
        }));

        const resetQuickFilter = listingResults.querySelector('[data-reset-quick-filter]');
        if (resetQuickFilter) resetQuickFilter.addEventListener('click', () => {
            const all = listingResults.querySelector('[data-quick-filter="all"]');
            all.click();
        });
    }

    const bookingList = document.querySelector('[data-booking-list]');
    if (bookingList) {
        const rows = Array.from(bookingList.querySelectorAll('[data-booking-status]'));
        const buttons = document.querySelectorAll('[data-booking-filter]');
        const empty = document.querySelector('[data-booking-filter-empty]');
        buttons.forEach(button => button.addEventListener('click', function () {
            const filter = this.dataset.bookingFilter;
            let visible = 0;
            buttons.forEach(item => item.classList.toggle('is-active', item === this));
            rows.forEach(row => {
                const matches = filter === 'all' || row.dataset.bookingStatus === filter;
                row.hidden = !matches;
                if (matches) visible += 1;
            });
            if (empty) empty.hidden = visible !== 0;
        }));
    }

    const profileForm = document.querySelector('[data-profile-form]');
    if (profileForm) {
        const editButton = document.querySelector('[data-profile-edit]');
        const saveButton = profileForm.querySelector('[data-profile-save]');
        const deleteButton = document.querySelector('[data-delete-account]');
        const fields = profileForm.querySelectorAll('input:not([type="hidden"]), textarea');
        editButton?.addEventListener('click', () => {
            fields.forEach(field => field.readOnly = false);
            saveButton.hidden = false;
            editButton.hidden = true;
            fields[0]?.focus();
        });
        deleteButton?.addEventListener('click', (event) => {
            if (!confirm('Delete your account and all bookings? This cannot be undone.')) event.preventDefault();
        });
    }

    const backToTop = document.querySelector('[data-back-to-top]');
    if (backToTop) {
        const updateBackToTop = () => backToTop.classList.toggle('is-visible', window.scrollY > 420);
        window.addEventListener('scroll', updateBackToTop, { passive: true });
        updateBackToTop();
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    const newsletter = document.querySelector('[data-footer-newsletter]');
    if (newsletter) {
        const email = newsletter.querySelector('input[type="email"]');
        const note = newsletter.querySelector('[data-footer-newsletter-note]');
        email.addEventListener('input', () => {
            const ready = email.validity.valid && email.value.length > 0;
            note.textContent = ready ? 'Looks good. Ready when you are.' : 'One useful note. Never noise.';
            note.classList.toggle('is-ready', ready);
        });
    }

    const loginForm = document.querySelector('[data-login-form]');
    if (loginForm) {
        const password = loginForm.querySelector('#login-password');
        const passwordToggle = loginForm.querySelector('[data-password-toggle]');
        const email = loginForm.querySelector('#login-email');
        const emailState = loginForm.querySelector('[data-email-state]');

        passwordToggle.addEventListener('click', () => {
            const isVisible = password.type === 'text';
            password.type = isVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            passwordToggle.innerHTML = `<i class="fas fa-eye${isVisible ? '' : '-slash'}"></i>`;
        });

        const updateEmailState = () => emailState.classList.toggle('is-ready', email.validity.valid && email.value.length > 0);
        email.addEventListener('input', updateEmailState);
        updateEmailState();
    }

    const adminSidebar = document.querySelector('[data-admin-sidebar]');
    const adminMenuToggle = document.querySelector('[data-admin-menu-toggle]');
    const adminMenuClosers = document.querySelectorAll('[data-admin-menu-close]');
    if (adminSidebar && adminMenuToggle) {
        const setAdminMenu = (open) => {
            adminSidebar.classList.toggle('is-open', open);
            document.querySelector('.admin-sidebar-backdrop')?.classList.toggle('is-visible', open);
            adminMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.classList.toggle('admin-menu-open', open);
        };
        adminMenuToggle.addEventListener('click', () => setAdminMenu(true));
        adminMenuClosers.forEach(closer => closer.addEventListener('click', () => setAdminMenu(false)));
        adminSidebar.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setAdminMenu(false)));
    }
});

// Booking date validation
function validateBookingForm(form) {
    const f = new Date(form.from_date.value);
    const t = new Date(form.to_date.value);
    if (f >= t) { alert('Return date must be after pickup date'); return false; }
    return true;
}
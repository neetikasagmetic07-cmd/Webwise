document.addEventListener('click', function (event) {
	const control = event.target.closest('[data-scroll-target][data-scroll-direction]');

	if (!control) {
		return;
	}

	const rail = document.getElementById(control.dataset.scrollTarget);
	const direction = control.dataset.scrollDirection === 'back' ? -1 : 1;

	if (!rail) {
		return;
	}

	rail.scrollBy({
		left: direction * rail.clientWidth * 0.8,
		behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
	});
});

document.addEventListener('click', function (event) {
	const tab = event.target.closest('[data-customer-service-category]');

	if (!tab) {
		return;
	}

	const page = tab.closest('.customer-service-page');
	const tabs = page.querySelectorAll('[data-customer-service-category]');
	const faqs = page.querySelectorAll('[data-customer-service-faq]');
	const selectedCategory = tab.dataset.customerServiceCategory;
	const categoryLabel = tab.querySelector('span').textContent;
	const heading = page.querySelector('[data-customer-service-heading]');
	const faqSection = page.querySelector('.customer-service-faq-section');

	tabs.forEach(function (categoryTab) {
		const isSelected = categoryTab === tab;
		categoryTab.classList.toggle('is-active', isSelected);
		categoryTab.setAttribute('aria-selected', String(isSelected));
	});

	let firstVisibleFaq = true;
	faqs.forEach(function (faq) {
		const isVisible = faq.dataset.customerServiceFaq === selectedCategory;
		faq.hidden = !isVisible;
		faq.open = isVisible && firstVisibleFaq;
		if (isVisible) {
			firstVisibleFaq = false;
		}
	});

	if (heading) {
		heading.textContent = categoryLabel;
	}

	if (faqSection) {
		faqSection.setAttribute('aria-labelledby', tab.id);
	}
});
